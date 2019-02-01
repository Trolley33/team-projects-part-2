<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Job;
use App\Equipment;
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
        
    }

    public function review_specialist ($id)
    {
        $specialist = User::find($id);
        if (!is_null($specialist))
        {
            if (PagesController::hasAccess(3))
            {
                // Get all info about specialist, for graphing.
                $rp = DB::select(DB::raw("
                        SELECT YEARWEEK( resolved_problems.created_at) AS 'yw', COUNT(*) AS 'count' FROM resolved_problems
                        WHERE resolved_problems.solved_by = '". $id ."' 
                        GROUP BY 
                        YEARWEEK(resolved_problems.created_at)
                        ORDER BY YEARWEEK(resolved_problems.created_at);
                    "));

                $tts = DB::select(DB::raw("
                        SELECT YEARWEEK( resolved_problems.created_at) AS 'yw', (AVG(TIME_TO_SEC(TIMEDIFF(resolved_problems.created_at, problems.created_at))) / 60) AS 'count' FROM resolved_problems
                        JOIN problems
                        ON problems.id = resolved_problems.problem_id
                        WHERE resolved_problems.solved_by = '". $id ."' 
                        GROUP BY 
                        YEARWEEK(resolved_problems.created_at)
                        ORDER BY YEARWEEK(resolved_problems.created_at);
                    "));
                $resolved = $this->sql_to_json($rp);
                $timeToSolve = $this->sql_to_json($tts);

                $data = array(
                    'title'=>'Review Specialist',
                    'desc'=>'Currently Reviewing a Specialist',
                    'specialist'=>$specialist,
                    'solved'=>$resolved,
                    'timeToSolve'=>$timeToSolve,
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
        
    }

    public function review_equipment_single ($id)
    {
        
    }

    public function review_software_single ($id)
    {
        
    }

}
