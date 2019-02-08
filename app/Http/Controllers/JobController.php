<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If user can access page
        if (PagesController::hasAccess(1))
        {
            // Get all jobs, with their department names, and number of employees.
            $jobs = Department::join('jobs', 'departments.id', '=', 'jobs.department_id')->leftJoin('users', 'jobs.id', '=', 'users.job_id')->selectRaw('jobs.id, jobs.title, departments.name, departments.id as dID, IFNULL(COUNT(users.id),0) as employees')->groupBy('jobs.id')->get();

            // Supply data to view.
            $data = array(
                'title' => "Job Information Viewer",
                'desc' => "View information on jobs.",
                'jobs' => $jobs,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Jobs'
            );
            return view('jobs.index')->with($data);
        }

        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // If user can access page
        if (PagesController::hasAccess(1))
        {
            // Get all departments.
            $departments = Department::all();
            $dept = null;
            // If supplied, pass given department to view for dropdown box.
            if (isset($_GET['department']))
            {
                $dept = Department::find($_GET['department']);
            }

            // By default use the first in the table.
            if (is_null($dept))
            {
                $dept = $departments->first();
            }

            // Supply data to view.
            $data = array(
                'title' => "Create New Job",
                'desc' => "For making a new job title.",
                'dept'=>$dept,
                'departments'=>$departments,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Jobs'
            );

            return view('jobs.create')->with($data);
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If user can access page
        if (PagesController::hasAccess(1))
        {            
            // Validate that the required fields have been supplied.
            $this->validate($request, [
                'department-select' => 'required',
                'jobTitle' => 'required'
            ]);

            // Find jobs with matching title as new title.
            $job = Job::where('title', $request->input('jobTitle'))->get();
            $department = Department::find($request->input('department-select'));
            // No matching jobs.
            if (count($job) == 0 && !is_null($department))
            {
                // Create job entry, redirect to department job is found in.
                $newJob = new Job();
                $newJob->title = $request->input('jobTitle');
                $newJob->department_id = $department->id;
                // Only the technical support jobs can have access level != 0, and they are immutable.
                $newJob->access_level = '0';
                $newJob->save();

                return redirect('/departments/'.$department->id)->with('success', 'Job Added');
            }

            // If matching job(s) are found.
            $data = array(
                'error'=>'Duplicate Job Name',
                'search'=>$request->input('jobTitle')
            );

            return redirect('/jobs')->with($data);
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // If user can access page
        if (PagesController::hasAccess(1))
        {
            // Find relevant job from ID.
            $job = Job::find($id);
            if (is_null($job))
            {
                return redirect()->back();
            }

            // Find out which department job is part of.
            $department = Department::find($job->department_id);
            // Get all users who have this job.
            $users = DB::table('users')->join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.access_level', 'departments.name')->where('jobs.id', '=', $id)->get();

            // Supply data to view.
            $data = array(
                'title' => $job->title,
                'desc' => "Display information on a job.",
                'users' => $users,
                'job' => $job,
                'department' => $department,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Jobs'
            );

            return view('jobs.show')->with($data);
        }

        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // If user can access page
        if (PagesController::hasAccess(1))
        {
            // Find relevant job from ID.
            $job = Job::find($id);
            if (is_null($job))
            {
                return redirect()->back();
            }
            $dept = Department::find($job->department_id);
            // Get all department info except tech support.
            $departments = Department::where('id', '!=', '1')->get();
            // Supply data to view.
            $data = array(
                'title' => "Edit Existing Job",
                'desc' => "For making a new department catagory.",
                'job'=>$job,
                'dept'=>$dept,
                'departments'=>$departments,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Jobs'
            );

            return view('jobs.edit')->with($data);
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $job = Job::find($id);
        if (!is_null($job))
        {
            // If user can access page
            if (PagesController::hasAccess(1))
            {            
                // Validate that the required fields have been supplied.
                $this->validate($request, [
                    'jobTitle' => 'required',
                    'department-select' => 'required'
                ]);

                // Find matching jobs that already have same title as one supplied.
                $result = Job::where('title', '=', $request->input('jobTitle'))->where('id', '!=', $id)->get();
                // No matches found.
                if (count($result) == 0)
                {
                    // Find job that needs to be edited.
                    $department = Department::find($request->input('department-select'));
                    // If job/department ID doesn't exist, redirect.
                    if (is_null($job) || is_null($department))
                    {
                        return redirect('/jobs')->with('error', 'Invalid data supplied.');
                    }
                    // Update info for title and save to database.
                    $job->title = $request->input('jobTitle');
                    $job->department_id = $department->id;
                    $job->save();

                    return redirect('/jobs')->with('success', 'Job Updated');
                }

                // Supply data to view.
                $data = array(
                    'error'=>'Duplicate Job Name',
                    'search'=>$request->input('jobTitle')
                );

                return redirect('/jobs')->with($data);
            }
            // No access redirects to login.
            return redirect('login')->with('error', 'Please log in first.');
        }
        return redirect('/jobs')->with('error', 'Sorry, something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // If user can access page
        if (PagesController::hasAccess(1))
        {
            $job = Job::find($id);
            if (is_null($job))
            {
                return redirect()->back();
            }
            $job->delete();

            $users = User::where('job_id', $id)->get();
            foreach ($users as $u) {
                $u->job_id = 0;
                $u->save();
            }

            return redirect('/jobs')->with('success', 'Job Deleted');
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }
}
