<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;

class UserController extends Controller
{
    public $operator_links = [
        ['href'=>'back','text'=>'back'],
        ['href'=>'operator','text'=>'Home'],
        ['href'=>'problems','text'=>'Problems'],
        ['href'=>'users','text'=>'Users'],
        ['href'=>'departments','text'=>'Departments'],
        ['href'=>'jobs','text'=>'Jobs'],
        ['href'=>'equipment','text'=>'Equipment'],
        ['href'=>'software','text'=>'Software'],
        ['href'=>'specialities','text'=>'Specialities']
    ];

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
            $info = DB::table('users')->join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.access_level', 'departments.name')->get();

            $data = array(
                'title' => "User information page.",
                'desc' => "Displays information on user accounts.",
                'info' => $info,
                'links' => $this->operator_links,
                'active' => 'Users'
            );

            return view('users.index')->with($data);
        }

        return redirect('login')->with('error', 'Please log in first.');
    }


    /**
     * Show the form for creating a new caller resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_caller()
    {
        if ($this->hasAccess(1))
        {

            $departments = DB::table('departments')->select('departments.*')->where('departments.id', '!=', '1')->get();

            $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '=', '0')->get();

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

            $job = $jobs->first();

            // If specified, grab the specified job,
            // for the dropdown box.
            if (isset($_GET['job']) && !empty($_GET['job']))
            {
                $job = Job::find($_GET['job']);
                if (is_null($job))
                {
                    $job = $jobs->first();
                }
            }

            $data = array(
                'title' => "Create New Caller Account",
                'desc' => "For creating employee accounts.",
                'departments' => $departments,
                'jobs' => $jobs,
                'dept'=>$dept,
                'job'=>$job,
                'links' => $this->operator_links,
                'active' => 'Users'
            );
            return view('users.create_caller')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for creating a new tech resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_tech_support()
    {
        if ($this->hasAccess(1))
        {


            $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '!=', '0')->get();

            $job = $jobs->first();

            // If specified, grab the specified job,
            // for the dropdown box.
            if (isset($_GET['job']) && !empty($_GET['job']))
            {
                $job = Job::find($_GET['job']);
                if (is_null($job))
                {
                    $job = $jobs->first();
                }
            }

            $data = array(
                'title' => "Create New Tech Support Account",
                'desc' => "For creating system related accounts.",
                'jobs' => $jobs,
                'job'=>$job,
                'links' => $this->operator_links,
                'active' => 'Users'
            );
            return view('users.create_tech_support')->with($data);
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
                'isCaller' => 'required',
                'empID' => 'required',
                'firstName' => 'required',
                'lastName' => 'required',
                'job-select' => 'required',
                'phone' => 'required'
            ]);


            // Caller account.
            if ($request->input('isCaller') == 'true')
            {
                $this->validate($request, [
                    'department-select' => 'required',
                ]);

                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

                if (count($info) ==  0)
                {
                    $caller = new User;
                    $caller->employee_id = $request->input('empID');
                    $caller->username = '---';
                    $caller->password = '';
                    $caller->forename = $request->input('firstName');
                    $caller->surname = $request->input('lastName');
                    $caller->job_id = $request->input('job-select');
                    $caller->phone_number = $request->input('phone');
                    $caller->save();

                    return redirect('/users')->with('success', 'Caller Added');
                }

                $data = array(
                    'error'=>'Duplicate Employee ID',
                    'search'=>$request->input('empID')
                );

                return redirect('/users')->with($data);
            }

            // System account.
            elseif ($request->input('isCaller') == 'false')
            {
                $this->validate($request, [
                    'username' => 'required',
                    'pass' => 'required',
                    'pass2' => 'required'
                ]);


                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

                if (count($info) ==  0)
                {
                    $user = new User;
                    $user->employee_id = $request->input('empID');
                    $user->username = $request->input('username');
                    $user->password = $request->input('pass');
                    $user->forename = $request->input('firstName');
                    $user->surname = $request->input('lastName');
                    $user->job_id = $request->input('job-select');
                    $user->phone_number = $request->input('phone');
                    $user->save();

                    return redirect('/users')->with('success', 'System Account Added');
                }

                $data = array(
                    'error'=>'Duplicate Employee ID',
                    'search'=>$request->input('empID')
                );

                return redirect('/users')->with($data);
            }

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
            $user = User::find($id);

            $info = DB::table('jobs')->join('users', 'jobs.id', '=', 'users.job_id')->join('departments' , 'jobs.department_id', '=', 'departments.id')->select( 'jobs.title', 'jobs.access_level', 'departments.name')->where('users.id', '=', $id)->get()->first();

            if (!is_null($user) && !is_null($info))
            {
                $data = array(
                    'title' => "User Viewer.",
                    'desc' => "View user account information.",
                    'user' => $user,
                    'job_info' => $info,
                    'links' => $this->operator_links,
                    'active' => 'Users'
                );

                return view('users.show')->with($data);
            }
            return "Error completing that request.";
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
        if ($this->hasAccess(1))
        {
            $user = User::find($id);
            $job = Job::find($user->job_id);
            if (!is_null($user))
            {
                if ($job->access_level == 0)
                {

                    $departments = DB::table('departments')->select('departments.*')->where('departments.id', '!=', '1')->get();

                    $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '=', '0')->get();

                    $data = array(
                        'title' => "Edit Caller Account.",
                        'desc' => "For changing details about a caller account.",
                        'user'=>$user,
                        'departments' => $departments,
                        'jobs' => $jobs,
                        'links' => $this->operator_links,
                        'active' => 'Users'
                    );

                    return view('users.edit_caller')->with($data);
                }

                else
                {
                    $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '!=', '0')->get();

                    $data = array(
                        'title' => "Edit System Account.",
                        'desc' => "For changing details about an account related to the system.",
                        'user'=>$user,
                        'jobs' => $jobs,
                        'links' => $this->operator_links,
                        'active' => 'Users'
                    );

                    return view('users.edit_tech')->with($data);
                }
            }

            return redirect('/users');
        }
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
        if ($this->hasAccess(1))
        {
            $this->validate($request, [
                'isCaller' => 'required',
                'empID' => 'required',
                'firstName' => 'required',
                'lastName' => 'required',
                'job-select' => 'required',
                'phone' => 'required'
            ]);


            // Caller account.
            if ($request->input('isCaller') == 'true')
            {
                $this->validate($request, [
                    'department-select' => 'required',
                ]);

                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

                if (count($info) !=  0)
                {
                    if ($info->first()->id == $id)
                    {
                        $caller = User::find($id);
                        $caller->employee_id = $request->input('empID');
                        $caller->forename = $request->input('firstName');
                        $caller->surname = $request->input('lastName');
                        $caller->job_id = $request->input('job-select');
                        $caller->phone_number = $request->input('phone');
                        $caller->save();

                        return redirect("/users/$id")->with('success', 'Caller Info Updated');
                    }
                }

                $data = array(
                    'error'=>'Duplicate Employee ID',
                    'search'=>$request->input('empID')
                );

                return redirect("/users/$id")->with($data);
            }

            // System account.
            elseif ($request->input('isCaller') == 'false')
            {
                $this->validate($request, [
                    'username' => 'required',
                    'pass' => 'required',
                    'pass2' => 'required'
                ]);


                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();
                if (count($info) !=  0)
                {
                    if ($info->first()->id == $id)
                    {
                        $user = User::find($id);
                        $user->employee_id = $request->input('empID');
                        $user->username = $request->input('username');
                        $user->password = $request->input('pass');
                        $user->forename = $request->input('firstName');
                        $user->surname = $request->input('lastName');
                        $user->job_id = $request->input('job-select');
                        $user->phone_number = $request->input('phone');
                        $user->save();

                        return redirect("/users/$id")->with('success', 'System Account Info Updated');
                    }
                }

                $data = array(
                    'error'=>'Duplicate Employee ID',
                    'search'=>$request->input('empID')
                );

                return redirect("/users/$id")->with($data);
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
;    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->hasAccess(1))
        {
            $user = User::find($id);
            $user->delete();
            return redirect('/users')->with('success', 'Account Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
