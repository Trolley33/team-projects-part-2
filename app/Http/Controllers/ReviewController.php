<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Job;
use App\Reassignments;

class ReviewController extends Controller
{
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
	        			SELECT YEAR( resolved_problems.created_at) AS 'year', MONTH( resolved_problems.created_at) AS 'month', DAY( resolved_problems.created_at) AS 'day', COUNT(*) AS 'Solved' FROM resolved_problems
	        			WHERE resolved_problems.solved_by = '". $id ."' 
	        			GROUP BY 
	        			DAY(resolved_problems.created_at),
	        			MONTH(resolved_problems.created_at), 
	        			YEAR(resolved_problems.created_at)
	        			ORDER BY YEAR(resolved_problems.created_at),
	        			MONTH(resolved_problems.created_at), 
	        			DAY(resolved_problems.created_at);
	        		"));

	        	
	        	$rp = 

	            $data = array(
	                'title'=>'Review Specialist',
	                'desc'=>'Currently Reviewing a Specialist',
	                'specialist'=>$specialist,
	                'data'=>$points,
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

    public function sql_to_json ($result)
    {
    	$points = array();
    	foreach ($result as $row) 
    	{
    		$point = array();
    		$point['x'] = sprintf('%04d-%02d-%02d', $row->year, $row->month, $row->day);
    		$point['y'] = $row->count;
    		array_push($points, $point);
    	}

    	return $points;
    }
}
