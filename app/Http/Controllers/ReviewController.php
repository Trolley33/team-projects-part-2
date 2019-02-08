<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Job;
use App\Equipment;
use App\Software;
use App\Call;
use App\ProblemType;
use App\Reassignments;

class ReviewController extends Controller
{
    /**
     * Converts SQL result into graph friendly array, which is converted to json in view.
    */
    public static function sql_to_json ($result, $mode)
    {
    	$points = array();
    	foreach ($result as $row) 
    	{
    		$point = array();
            if ($mode == 0)
            {
                $yw = strval($row->yw);
        		$point['x'] = sprintf('%04d-W%02d-7', substr($yw, 0, 4), substr($yw, 4, 6));
        		$point['y'] = $row->count;
            }
            elseif ($mode == 1) {
                $point['x'] = $row->label;
                $point['y'] = $row->count;
            }
    		array_push($points, $point);
    	}

    	return $points;
    }
    /**
     * Displays all database tables, future tables must be added manually.
    */
    public function show_tables ()
    {
        if (!PagesController::hasAccess(3))
        {
            return redirect('/login')->with('error','Please log in first.');
        }

        $table_names = array(
            'users',
            'jobs',
            'departments',
            'speciality',
            'timeoff',
            'problems',
            'calls',
            'problem_types',
            'resolved_problems',
            'affected_hardware',
            'affected_software',
            'equipment',
            'software',
            'reassignments',
            'importance',
        );

        $tables = array();

        foreach ($table_names as $key => $name) {
            $count = DB::select(DB::raw("SELECT COUNT(*) as c FROM $name"))[0]->c;
            $tables[$name] = $count;
        }

        $data = array(
                'title'=>'Export Data',
                'desc'=>'Select tables to export data on.',
                'tables'=>$tables,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

        return view('pages.analyst.export')->with($data);
    }

    /**
     * Exports data on selected tables to CSV file, and downloads it.
    */
    public function export (Request $request)
    {
        if (!PagesController::hasAccess(3))
        {
            return redirect('/login')->with('error','Please log in first.');
        }

        $this->validate($request, [
            'table'=>'required'
        ]);

        $tables = $request->input('table');

        header('Content-Type: text/csv; charset=utf-8');  
        header('Content-Disposition: attachment; filename=data.csv');  
        $output = fopen("php://output", "w");

        $table_names = array(
            'users',
            'jobs',
            'departments',
            'speciality',
            'timeoff',
            'problems',
            'calls',
            'problem_types',
            'resolved_problems',
            'affected_hardware',
            'affected_software',
            'equipment',
            'software',
            'reassignments',
            'importance',
        );

        foreach ($tables as $table) {
            if (!in_array($table, $table_names))
            {
                continue;
            }

            fputcsv($output, array('-', '-', $table.' table', '-', '-'));
            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            fputcsv($output, $columns);

            $rows = DB::select(DB::raw("SELECT * FROM $table"));
            foreach ($rows as $row) {
                fputcsv($output, (array)$row);
            }
        }

        fclose($output);

        return "";        
    }

    /**
     * Shows 2 graphs, 1 with modifiable data on all information, 1 with data on most common problem type.
    */
    public function review ()
    {
        if (PagesController::hasAccess(3))
        {
            // Get all info about specialist, for graphing.
            $datasets = array();

            // Problems Placed
            $pp = DB::select(DB::raw("
                SELECT YEARWEEK(problems.created_at) AS 'yw', COUNT(*) AS 'count' FROM problems
                GROUP BY 
                yw
                ORDER BY yw;
            "));
            // Resolved Problems
            $rp = DB::select(DB::raw("
                SELECT YEARWEEK( resolved_problems.created_at) AS 'yw', COUNT(*) AS 'count' FROM resolved_problems
                GROUP BY 
                yw
                ORDER BY yw;
            "));
            // Calls Placed
            $cp = DB::select(DB::raw("
                SELECT YEARWEEK( calls.created_at) AS 'yw', COUNT(*) AS 'count' FROM calls
                GROUP BY 
                yw
                ORDER BY yw;
            "));

            $pt = DB::select(DB::raw("
                SELECT parent.description as 'label', COUNT(*) AS 'count' FROM problem_types parent
                LEFT JOIN problem_types pt
                ON pt.parent = parent.id
                JOIN problems
                ON (problems.problem_type = pt.id) OR (problems.problem_type = parent.id)
                WHERE parent.parent = -1
                GROUP BY parent.id
                ORDER BY count DESC;
            "));

            array_push($datasets, array('data' => $this->sql_to_json($pp, 0), 'yLabel' => "Problems Placed Per Week", 'color' => 'rgb(230,90,90)'));
            array_push($datasets, array('data' => $this->sql_to_json($rp, 0), 'yLabel' => "Problems Solved Per Week", 'color' => 'rgb(30,128,128)'));
            array_push($datasets, array('data' => $this->sql_to_json($cp, 0), 'yLabel' => "Calls Placed Per Week", 'color' => 'rgb(60,230,60)'));

            $most_pt = $this->sql_to_json($pt, 1);

             $data = array(
                'title'=>'Review Activity',
                'desc'=>'Review all activity',
                'datasets'=>$datasets,
                'most_pt'=>$most_pt,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.index')->with($data);
        }

        return redirect('/login')->with('error','Please log in first.');
    }

    /**
     * Shows list of specialists (and operators) who can be reviewed.
    */
    public function review_specialists ()
    {
        if (PagesController::hasAccess(3))
        {
            $specialists = User::join('jobs', 'users.job_id', '=', 'jobs.id')->where('jobs.access_level', '=', '2')->orWhere('jobs.access_level', '=', '1')->select('users.*')->get();

            $data = array(
                'title'=>'Review Helper',
                'desc'=>'Select a Helper to Review',
                'specialists'=>$specialists,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.specialists.index')->with($data);
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }
    /**
     * Shows list of callers who can be reviewed. 
    */
    public function review_callers ()
    {
        if (PagesController::hasAccess(3))
        {
            $callers = User::all();

            $data = array(
                'title'=>'Review Caller',
                'desc'=>'Select a Caller to Review',
                'callers'=>$callers,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );
            return view('review.callers.index')->with($data);
        }
        return redirect('/login')->with('error', 'Please log in first.');
    }

    /**
     * Shows list of equipment that can be reviewed.
    */
    public function review_equipment ()
    {
        if (PagesController::hasAccess(3))
        {
            $equip = Equipment::all();

            $data = array(
                'title'=>'Review Equipment',
                'desc'=>'Select Equipment to Review',
                'equipment'=>$equip,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.equipment.index')->with($data);
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }

    /**
     * Shows list of software that can be reviewed.
    */
    public function review_software ()
    {
        if (PagesController::hasAccess(3))
        {
            $soft = Software::all();

            $data = array(
                'title'=>'Review Software',
                'desc'=>'Select Software to Review',
                'software'=>$soft,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.software.index')->with($data);
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }
    /**
     * Shows list of problem types that can be reviewed. 
    */
    public function review_problem_types ()
    {
        if (PagesController::hasAccess(3))
        {
            $problem_type_table = ProblemType::where('parent', '-1')->get();

            $data = array(
                'title'=>'Review Problem Types',
                'desc'=>'Select Problem Types to Review',
                'types'=>$problem_type_table,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.problem_types.index')->with($data);
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }

    /**
     * Shows graph with data about specialist.
    */
    public function review_specialist ($id)
    {
        $specialist = User::find($id);
        if (!is_null($specialist))
        {
            if (PagesController::hasAccess(3))
            {
                // Get all info about specialist, for graphing.
                $datasets = array();

                $rp = DB::select(DB::raw("
                        SELECT YEARWEEK( resolved_problems.created_at) AS 'yw', COUNT(*) AS 'count' FROM resolved_problems
                        WHERE resolved_problems.solved_by = '". $id ."' 
                        GROUP BY 
                        yw
                        ORDER BY yw;
                    "));

                $tts = DB::select(DB::raw("
                        SELECT YEARWEEK( resolved_problems.created_at) AS 'yw', (AVG(TIME_TO_SEC(TIMEDIFF(resolved_problems.created_at, problems.created_at))) / 60) AS 'count' FROM resolved_problems
                        JOIN problems
                        ON problems.id = resolved_problems.problem_id
                        WHERE resolved_problems.solved_by = '". $id ."' 
                        GROUP BY 
                        yw
                        ORDER BY yw;
                    "));

                $ra = DB::select(DB::raw("
                        SELECT YEARWEEK(reassignments.created_at) AS 'yw', COUNT(*) AS 'count' FROM reassignments
                        WHERE reassignments.specialist_id = '". $id ."' 
                        GROUP BY 
                        yw
                        ORDER BY yw;
                    "));

                array_push($datasets, array('data' => $this->sql_to_json($rp, 0), 'yLabel' => "Problems Solved Per Week", 'color' => 'rgb(30,128,128)'));

                array_push($datasets, array('data' => $this->sql_to_json($tts, 0), 'yLabel' => "AVG Time to Solve Problems (Minutes)", 'color' => 'rgb(191, 53, 84)'));

                array_push($datasets, array('data' => $this->sql_to_json($ra, 0), 'yLabel' => "Number of Reassignments", 'color' => 'rgb(90, 120, 50)'));

                $data = array(
                    'title'=>'Review Specialist',
                    'desc'=>'Currently Reviewing a Specialist',
                    'specialist'=>$specialist,
                    'datasets'=>$datasets,
                    'links'=>PagesController::getAnalystLinks(),
                    'active'=>'Review'
                );

                return view('review.specialists.show')->with($data);
            }

            return redirect('/login')->with('error', 'Please log in first.');

            }

        return redirect('/review/specialists')->with('error', 'Sorry, something went wrong.');
    }
    /**
     * Shows graph with data about caller.
    */
    public function review_caller ($id)
    {
        $caller = User::find($id);
        if (!is_null($caller))
        {
            if (PagesController::hasAccess(3))
            {
                // Get all info about caller, for graphing.
                $datasets = array();

                $rp = DB::select(DB::raw("
                        SELECT YEARWEEK(calls.created_at) AS 'yw', COUNT(*) AS 'count' FROM calls
                        WHERE calls.caller_id = '". $id ."' 
                        GROUP BY yw
                        ORDER BY yw;
                    "));

                array_push($datasets, array('data' => $this->sql_to_json($rp, 0), 'yLabel' => "Calls Made Per Week", 'color' => 'rgb(155,120,50)'));

                $problem_type_table = Call::join('problems', 'problems.id', '=', 'calls.problem_id')->join('problem_types', 'problem_types.id', '=', 'problems.problem_type')->leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('problem_types.*, IFNULL(parents.description,0) as parent_description, COUNT(problem_types.id) as count')->where('calls.caller_id', '=', $id)->groupBy('problem_types.id')->orderBy('count', 'desc')->get();

                $data = array(
                    'title'=>'Review Caller',
                    'desc'=>'Currently Reviewing a Caller',
                    'caller'=>$caller,
                    'types'=>$problem_type_table,
                    'datasets'=>$datasets,
                    'links'=>PagesController::getAnalystLinks(),
                    'active'=>'Review'
                );

                return view('review.callers.show')->with($data);
            }

            return redirect('/login')->with('error', 'Please log in first.');

            }

        return redirect('/review/callers')->with('error', 'Sorry, something went wrong.');
    }
    /**
     * Shows graph with data about equipment.
    */
    public function review_equipment_single ($id)
    {
        $equip = Equipment::find($id);
        if (!is_null($equip))
        {
            if (PagesController::hasAccess(3))
            {
                // Get all info about equipment, for graphing.
                $datasets = array();
                $i = DB::select(DB::raw("
                        SELECT YEARWEEK( affected_hardware.created_at) AS 'yw', COUNT(*) AS 'count' FROM affected_hardware
                        WHERE affected_hardware.equipment_id = '". $id ."' 
                        GROUP BY 
                        yw
                        ORDER BY yw;
                    "));

                array_push($datasets, array('data' => $this->sql_to_json($i, 0), 'yLabel' => "Related Problems", 'color' => 'rgb(155,30,155)'));

                $data = array(
                    'title'=>'Review Equipment',
                    'desc'=>'Currently Reviewing Equipment',
                    'equipment'=>$equip,
                    'datasets'=>$datasets,
                    'links'=>PagesController::getAnalystLinks(),
                    'active'=>'Review'
                );

                return view('review.equipment.show')->with($data);
            }

            return redirect('/login')->with('error', 'Please log in first.');

            }

        return redirect('/review/equipment')->with('error', 'Sorry, something went wrong.');
    }
    /**
     * Shows graph with data about software.
    */
    public function review_software_single ($id)
    {
        $soft = Software::find($id);
        if (!is_null($soft))
        {
            if (PagesController::hasAccess(3))
            {
                // Get all info about software, for graphing.
                $datasets = array();
                $i = DB::select(DB::raw("
                        SELECT YEARWEEK( affected_software.created_at) AS 'yw', COUNT(*) AS 'count' FROM affected_software
                        WHERE affected_software.software_id = '". $id ."' 
                        GROUP BY 
                        yw
                        ORDER BY yw;
                    "));

                array_push($datasets, array('data' => $this->sql_to_json($i, 0), 'yLabel' => "Related Problems", 'color' => 'rgb(30,155,155)'));

                $data = array(
                    'title'=>'Review Software',
                    'desc'=>'Currently Reviewing Software',
                    'software'=>$soft,
                    'datasets'=>$datasets,
                    'links'=>PagesController::getAnalystLinks(),
                    'active'=>'Review'
                );

                return view('review.software.show')->with($data);
            }

            return redirect('/login')->with('error', 'Please log in first.');

            }

        return redirect('/review/software')->with('error', 'Sorry, something went wrong.');
    }
    /**
     * Shows graph with data about problem type.
    */
    public function review_problem_type ($id)
    {
        $pt = ProblemType::find($id);
        if (!is_null($pt))
        {
            if (PagesController::hasAccess(3))
            {
                 // Get all info about software, for graphing.
                $datasets = array();
                $i = DB::select(DB::raw("
                        SELECT YEARWEEK(problems.created_at) AS 'yw', COUNT(*) AS 'count' FROM problems 
                        JOIN problem_types
                        ON problems.problem_type = problem_types.id
                        WHERE problem_types.id = '$id' OR problem_types.parent = '$id'
                        GROUP BY 
                        yw
                        ORDER BY yw;
                    "));

                array_push($datasets, array('data' => $this->sql_to_json($i, 0), 'yLabel' => "Problems Logged", 'color' => 'rgb(50,100,155)'));


                $parent = ProblemType::find($pt->parent);

                $data = array(
                    'title'=>'Review Problem Type',
                    'desc'=>'Currently Reviewing Problem Type',
                    'pt'=>$pt,
                    'parent'=>$parent,
                    'datasets'=>$datasets,
                    'links'=>PagesController::getAnalystLinks(),
                    'active'=>'Review'
                );

                return view('review.problem_types.show')->with($data);
            }

            return redirect('/login')->with('error', 'Please log in first.');
        }

        return redirect('/review/problem_types')->with('error', 'Sorry, something went wrong.');
    }

}
