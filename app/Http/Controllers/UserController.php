<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;
use App\Speciality;
use App\ProblemType;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (PagesController::hasAccess(1))
        {
            $info = DB::table('users')->join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.access_level', 'departments.name')->get();

            $data = array(
                'title' => "User information page.",
                'desc' => "Displays information on user accounts.",
                'info' => $info,
                'links' => PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
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
                'links' => PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
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
                'links' => PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
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
                    'password' => 'required'
                ]);


                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

                if (count($info) ==  0)
                {
                    $user = new User;
                    $user->employee_id = $request->input('empID');
                    $user->username = $request->input('username');
                    $user->password = $request->input('password');
                    $user->forename = $request->input('firstName');
                    $user->surname = $request->input('lastName');
                    $user->job_id = $request->input('job-select');
                    $user->phone_number = $request->input('phone');
                    $user->save();

                    $level = Job::find($user->job_id)->access_level;

                    if ($level != 2)
                    {
                        return redirect('/users')->with('success', 'System Account Added');
                    }

                    return redirect('/users/'.$user->id.'/edit_specialism');
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

    public function edit_specialism ($id)
    {
        if (PagesController::hasAccess(1))
        {
            $problem_types = ProblemType::all();
            $user = User::find($id);

            $data = array(
                'title' => "Change User Specialism",
                'desc' => " ",
                'problem_types' => $problem_types,
                'user'=>$user,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Users'
            );

            return view('users.edit_specialism')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function add_specialism ($user_id, $pt_id)
    {
        if (PagesController::hasAccess(1))
        {
            $specialism = Speciality::where('specialist_id', '=', $user_id)->get()->first();
            if (is_null($specialism))
            {
                $specialism = new Speciality();
            }
            $specialism->specialist_id = $user_id;
            $specialism->problem_type_id = $pt_id;
            $specialism->save();

            return redirect('/users/'.$user_id)->with('Success', 'Speciality Added');
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
        if (PagesController::hasAccess(1))
        {
            $user = User::find($id);

            $info = DB::table('jobs')->join('users', 'jobs.id', '=', 'users.job_id')->join('departments' , 'jobs.department_id', '=', 'departments.id')->select( 'jobs.id as jID', 'jobs.title', 'jobs.access_level', 'departments.id as dID', 'departments.name')->where('users.id', '=', $id)->get()->first();

            $problem_type = Speciality::join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('speciality.specialist_id', '=', $id)->get()->first();

            if (!is_null($user) && !is_null($info))
            {
                $data = array(
                    'title' => "User Viewer.",
                    'desc' => "View user account information.",
                    'user' => $user,
                    'job_info' => $info,
                    'problem_type'=>$problem_type,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Users'
                );

                return view('users.show')->with($data);
            }
            return "Error completing that request.";
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function show_compact($id)
    {
        if (PagesController::hasAccess(1))
        {
            $user = User::find($id);

            $info = DB::table('jobs')->join('users', 'jobs.id', '=', 'users.job_id')->join('departments' , 'jobs.department_id', '=', 'departments.id')->select( 'jobs.id as jID', 'jobs.title', 'jobs.access_level', 'departments.id as dID', 'departments.name')->where('users.id', '=', $id)->get()->first();

            $problem_type = Speciality::join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('speciality.specialist_id', '=', $id)->get()->first();

            if (!is_null($user) && !is_null($info))
            {
                $data = array(
                    'title' => "User Viewer.",
                    'desc' => "View user account information.",
                    'user' => $user,
                    'job_info' => $info,
                    'problem_type'=>$problem_type,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Users'
                );

                return view('users.show_compact')->with($data);
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
        if (PagesController::hasAccess(1))
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
                        'problem_type'=>null,
                        'links' => PagesController::getOperatorLinks(),
                        'active' => 'Users'
                    );

                    return view('users.edit_caller')->with($data);
                }

                else
                {
                    $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '!=', '0')->get();

                    $problem_type = Speciality::join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('speciality.specialist_id', '=', $id)->get()->first();
                    
                    $data = array(
                        'title' => "Edit System Account.",
                        'desc' => "For changing details about an account related to the system.",
                        'user'=>$user,
                        'jobs' => $jobs,
                        'job'=>$job,
                        'problem_type'=>$problem_type,
                        'links' => PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
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
                    'password' => 'required'
                ]);


                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();
                if (count($info) !=  0)
                {
                    if ($info->first()->id == $id)
                    {
                        $user = User::find($id);
                        $user->employee_id = $request->input('empID');
                        $user->username = $request->input('username');
                        $user->password = $request->input('password');
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
        if (PagesController::hasAccess(1))
        {
            $user = User::find($id);
            $user->delete();

            $speciality = Speciality::where('specialist_id', '=', $id)->delete();
            
            return redirect('/users')->with('success', 'Account Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
