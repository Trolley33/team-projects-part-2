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

    // Workaround function for authemtication.
    public function hasAccess($level)
    {
        if (isset($_COOKIE['csrf']))
        {
            $cookie = $_COOKIE['csrf'];
        }
        else
        {
            return false;
        }
        
        $result = DB::table('users')->select('users.id')->where('users.remember_token', '=', $cookie)->get();

        if (!is_null($result))
        {
            $id = $result->first()->id;
            $user = User::find($id);
            $job = Job::find($user->job_id);
            if ($job->access_level == $level)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->hasAccess(1))
        {

            // $jobs = DB::table('jobs')->join('users', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->selectRaw('jobs.id, jobs.title, departments.name, COUNT(users.id) as employees')->groupBy('jobs.id')->get();

            $jobs = Department::join('jobs', 'departments.id', '=', 'jobs.department_id')->leftJoin('users', 'jobs.id', '=', 'users.job_id')->selectRaw('jobs.id, jobs.title, departments.name, IFNULL(COUNT(users.id),0) as employees')->groupBy('jobs.id')->get();

            $data = array(
                'title' => "Job Information Viewer",
                'desc' => "View information on jobs.",
                'jobs' => $jobs
            );

            return view('jobs.index')->with($data);
        }

        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->hasAccess(1))
        {
            $departments = Department::all();

            $dept = $departments->first();

            // If specified, grab the specified department,
            // for the dropdown box.
            if (isset($_GET['department']) && !empty($_GET['department']))
            {
                $dept = Department::find($_GET['department']);
                if (is_null($dept))
                {
                    $dept = $departments->first();
                }
            }

            $data = array(
                'title' => "Create New Job",
                'desc' => "For making a new job title.",
                'dept'=>$dept,
                'departments'=>$departments
            );

            return view('jobs.create')->with($data);
        }
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
        if ($this->hasAccess(1))
        {            
            $this->validate($request, [
                'department-select' => 'required',
                'jobTitle' => 'required',
                'accessLevel'=>'required'
            ]);

            $job = Job::where('title', $request->input('jobTitle'))->get();

            if (count($job) == 0)
            {
                $newJob = new Job();
                $newJob->title = $request->input('jobTitle');
                $newJob->department_id = $request->input('department-select');
                $newJob->access_level = $request->input('accessLevel');
                $newJob->save();

                return redirect('/departments/'+$request->input('department-select'))->with('success', 'Job Added');
            }

            $data = array(
                'error'=>'Duplicate Job Name',
                'search'=>$request->input('jobTitle')
            );

            return redirect('/jobs')->with($data);
        }
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
        if ($this->hasAccess(1))
        {
            $job = Job::find($id);

            $users = DB::table('users')->join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.access_level', 'departments.name')->where('jobs.id', '=', $id)->get();

            $data = array(
                'title' => $job->title,
                'desc' => "Display information on a job.",
                'users' => $users,
                'job' => $job
            );

            return view('jobs.show')->with($data);
        }

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
