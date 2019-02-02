<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Job;
use App\Equipment;
use App\Software;
use App\Reassignments;

class ReviewController extends Controller
{
    public static function sql_to_json ($result)
    {
    	$points = array();
    	foreach ($result as $row) 
    	{
    		$point = array();
            $yw = strval($row->yw);
    		$point['x'] = sprintf('%04d-W%02d-7', substr($yw, 0, 4), substr($yw, 4, 6));
    		$point['y'] = $row->count;
    		array_push($points, $point);
    	}

    	return $points;
    }

    public function review ()
    {
        if (PagesController::hasAccess(3))
        {
            // Get all info about specialist, for graphing.
            $datasets = array();

            $rp = DB::select(DB::raw("
                SELECT YEARWEEK( resolved_problems.created_at) AS 'yw', COUNT(*) AS 'count' FROM resolved_problems
                GROUP BY 
                yw
                ORDER BY yw;
            "));            

            array_push($datasets, array('data' => $this->sql_to_json($rp), 'yLabel' => "Problems Solved Per Week", 'color' => 'rgb(30,128,128)'));

             $data = array(
                'title'=>'Review Activity',
                'desc'=>'Review all activity',
                'datasets'=>$datasets,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.index')->with($data);
        }

        return redirect('/login')->with('error','Please log in first.');
    }

    public function review_specialists ()
    {
        if (PagesController::hasAccess(3))
        {
            $specialists = User::join('jobs', 'users.job_id', '=', 'jobs.id')->where('jobs.access_level', '=', '2')->select('users.*')->get();

            $data = array(
                'title'=>'Review Specialist',
                'desc'=>'Select a Specialist to Review',
                'specialists'=>$specialists,
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Review'
            );

            return view('review.specialists.index')->with($data);
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }

    public function review_callers ()
    {
        if (PagesController::hasAccess(3))
        {
            $callers = User::join('jobs', 'users.job_id', '=', 'jobs.id')->where('jobs.access_level', '=', '0')->select('users.*')->get();

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

                array_push($datasets, array('data' => $this->sql_to_json($rp), 'yLabel' => "Problems Solved Per Week", 'color' => 'rgb(30,128,128)'));

                array_push($datasets, array('data' => $this->sql_to_json($tts), 'yLabel' => "AVG Time to Solve Problems (Minutes)", 'color' => 'rgb(191, 53, 84)'));

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

                array_push($datasets, array('data' => $this->sql_to_json($rp), 'yLabel' => "Calls Made Per Week", 'color' => 'rgb(155,120,50)'));


                $data = array(
                    'title'=>'Review Caller',
                    'desc'=>'Currently Reviewing a Caller',
                    'caller'=>$caller,
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

                array_push($datasets, array('data' => $this->sql_to_json($i), 'yLabel' => "Related Problems", 'color' => 'rgb(155,30,155)'));

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

                array_push($datasets, array('data' => $this->sql_to_json($i), 'yLabel' => "Related Problems", 'color' => 'rgb(30,155,155)'));

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

}
