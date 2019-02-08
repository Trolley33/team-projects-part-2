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
use App\TimeOff;
use App\Problem;
use App\ResolvedProblem;
use App\Call;
use App\Reassignments;
use App\Skill;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Get list of all users, and their info, excluding 'default user' (id=0).
            $info = DB::table('users')->join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.access_level', 'departments.name')->where('users.id', '!=', '0')->get();

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
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Get all departments that aren't tech support.
            $departments = DB::table('departments')->select('departments.*')->where('departments.id', '!=', '1')->get();

            // Get all jobs that aren't tech support.
            $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '=', '0')->get();

            // Default to first department found.
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

            // Default to first job found.
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
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Get tech support jobs.
            $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '!=', '0')->get();
            // Default to first found.
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
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Check all required data is supplied.
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
                // Caller needs department to be supplied.
                $this->validate($request, [
                    'department-select' => 'required',
                ]);

                // Check no users exist with the employee ID supplied.
                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

                if (count($info) ==  0)
                {
                    // Create new caller account, defaults for not needed fields.
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
                // System account needs username and password.
                $this->validate($request, [
                    'username' => 'required',
                    'password' => 'required'
                ]);

                // Check no users exist with the employee ID supplied.
                $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

                if (count($info) ==  0)
                {
                    // Create new caller account.
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

                    // Normal accounts just redirect normally.
                    if ($level != 2)
                    {
                        return redirect('/users')->with('success', 'System Account Added');
                    }
                    // If a specialist was created, redirect to page where specialism is chosen.
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
    /**
     * Selects which problem type user specialises in.
     */
    public function edit_specialism ($id)
    {
        // Get user from ID, check it exists.
        $user = User::find($id);
        if (!is_null($user))
        {
            // Get current specialism.
            $specialism = Speciality::where('specialist_id', '=', $user->id)->first();
            // If specialism exists, set ID for autoselecting.
            $pt_id = null;
            if (!is_null($specialism))
            {
                $pt_id = $specialism->problem_type_id;
            }
            // Get list of problem types.
            $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('problem_types.*, IFNULL(parents.description,0) as parent_description')->get();

            // If user is operator.
            if (PagesController::hasAccess(1))
            {
                $data = array(
                    'title' => "Change User Specialism",
                    'desc' => " ",
                    'problem_types' => $problem_types,
                    'user'=>$user,
                    'pt_id'=>$pt_id,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Users'
                );

                return view('users.edit_specialism')->with($data);
            }
            // If user is specialist.
            elseif (PagesController::hasAccess(2)) 
            {
                // Specialists can only edit their own specialism.
                $me = PagesController::getCurrentUser();
                if ($me->id == $user->id)
                {
                    $data = array(
                        'title' => "Change User Specialism",
                        'desc' => " ",
                        'problem_types' => $problem_types,
                        'user'=>$user,
                        'pt_id'=>$pt_id,
                        'links' => PagesController::getSpecialistLinks(),
                        'active' => 'Users'
                    );
                    return view('users.edit_specialism')->with($data);
                    }
                return redirect('/')->with('error', 'Sorry, something went wrong.');
            }
            return redirect('login')->with('error', 'Please log in first.');
        }
        return redirect('/')->with('error', 'Sorry, something went wrong.');
    }

    /**
     * Adds problem type specialism to database.
     */
    public function add_specialism ($user_id, $pt_id)
    {
        // Get user/problem type and check exists.
        $user = User::find($user_id);
        $pt = ProblemType::find($pt_id);
        if (!is_null($user) && !is_null($pt))
        {
            // If user is operator.
            if (PagesController::hasAccess(1))
            {
                // Get current specialism, if it exists.
                // Otherwise create a new one.
                $specialism = Speciality::where('specialist_id', '=', $user_id)->first();
                if (is_null($specialism))
                {
                    $specialism = new Speciality();
                }
                // Set fields.
                $specialism->specialist_id = $user_id;
                $specialism->problem_type_id = $pt_id;
                $specialism->save();

                return redirect('/users/'.$user_id)->with('Success', 'Speciality Added');
            }
            // If user is specialist.
            if (PagesController::hasAccess(2))
            {
                // Specialists can only access their own specialism.
                $me = PagesController::getCurrentUser();
                if ($me->id == $user->id)
                {
                    // Get current specialism, if it exists.
                    // Otherwise create a new one.
                    $specialism = Speciality::where('specialist_id', '=', $user_id)->first();
                    if (is_null($specialism))
                    {
                        $specialism = new Speciality();
                    }
                    // Set fields.
                    $specialism->specialist_id = $user_id;
                    $specialism->problem_type_id = $pt_id;
                    $specialism->save();

                    return redirect('/specialist')->with('Success', 'Speciality Changed');
                }
                return redirect('/specialist')->with('error', 'Sorry, something went wrong.');
            }
            return redirect('/login')->with('error', 'Please log in first.');
        }
        return redirect('/')->with('error', 'Sorry, something went wrong.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get user, check if exists.
        $user = User::find($id);
        if (is_null($user))
        {
            return redirect()->back();
        }
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Get user speciality, if set.
            $problem_type = Speciality::join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('speciality.specialist_id', '=', $id)->get()->first();
            $parent = null;
            if (!is_null($problem_type))
            {
                $parent = ProblemType::find($problem_type->parent);
            }
            // Get information about this user.
            $info = Job::join('users', 'jobs.id', '=', 'users.job_id')->join('departments' , 'jobs.department_id', '=', 'departments.id')->select( 'jobs.id as jID', 'jobs.title', 'jobs.access_level', 'departments.id as dID', 'departments.name')->where('users.id', $user->id)->first();
            
            if (!is_null($info))
            {
                // Get any upcoming time off, within next 7 days.
                $timeoff = TimeOff::where('user_id', '=', $id)->whereRaw('DATE_ADD(DATE(NOW()), INTERVAL 7 DAY) >= timeoff.startDate AND DATE(NOW()) <= timeoff.endDate')->orderBy('created_at', 'desc')->first();
                $data = array(
                    'title' => "User Viewer.",
                    'desc' => "View user account information.",
                    'user' => $user,
                    'job_info' => $info,
                    'problem_type'=>$problem_type,
                    'parent'=>$parent,
                    'timeoff'=>$timeoff,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Users'
                );
                return view('users.show')->with($data);
            }
            return redirect()->back();
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /**
     * Shows compacted view of user, more modal friendly.
     */
    public function show_compact($id)
    {
        // Get user, check if exists.
        $user = User::find($id);
        if (is_null($user))
        {
            return redirect()->back();
        }
        // If user is operator or specialist.
        if (PagesController::hasAccess(1)||PagesController::hasAccess(2))
        {
            // Get information about this user.
            $info = DB::table('jobs')->join('users', 'jobs.id', '=', 'users.job_id')->join('departments' , 'jobs.department_id', '=', 'departments.id')->select( 'jobs.id as jID', 'jobs.title', 'jobs.access_level', 'departments.id as dID', 'departments.name')->where('users.id', '=', $id)->first();
            // Get user speciality, if set.
            $problem_type = Speciality::join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('speciality.specialist_id', '=', $id)->first();

            $parent = null;
            if (!is_null($problem_type))
            {
                $parent = ProblemType::find($problem_type->parent);
            }
            if (!is_null($info))
            {
                // Get any upcoming time off, within next 7 days.
                $timeoff = TimeOff::where('user_id', '=', $id)->whereRaw('DATE_ADD(DATE(NOW()), INTERVAL 7 DAY) >= timeoff.startDate AND DATE(NOW()) <= timeoff.endDate')->orderBy('created_at', 'desc')->first();
                // Supply information on account viewing data, so the view knows whether to have certain links active.
                $viewer = PagesController::getCurrentUser();
                $job = Job::find($viewer->job_id);
                $level = $job->access_level;
                $data = array(
                    'title' => "User Viewer.",
                    'desc' => "View user account information.",
                    'level'=>$level,
                    'user' => $user,
                    'job_info' => $info,
                    'problem_type'=>$problem_type,
                    'parent'=>$parent,
                    'timeoff'=>$timeoff,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Users'
                );
                return view('users.show_compact')->with($data);
            }
            return redirect()->back();
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
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Get user, check if it exists.
            $user = User::find($id);
            if (!is_null($user))
            {
                // Get job from user, and check what type of user we are editing.
                $job = Job::find($user->job_id);
                // Caller edit.
                if ($job->access_level == 0)
                {
                    // Get list of departments and jobs for drop downs (not tech support accounts/department).
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
                // System account edit.
                else
                {
                    // Get list of tech support accounts for dropdown.
                    $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '!=', '0')->get();
                    // Get specialism of user (if set).
                    $problem_type = Speciality::join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('speciality.specialist_id', '=', $id)->get()->first();

                    $parent = null;
                    if (!is_null($problem_type))
                    {
                        $parent = ProblemType::find($problem_type->parent);
                    }
                    
                    $data = array(
                        'title' => "Edit System Account.",
                        'desc' => "For changing details about an account related to the system.",
                        'user'=>$user,
                        'jobs' => $jobs,
                        'job'=>$job,
                        'problem_type'=>$problem_type,
                        'parent'=>$parent,
                        'links' => PagesController::getOperatorLinks(),
                        'active' => 'Users'
                    );
                    return view('users.edit_tech')->with($data);
                }
            }
            return redirect('/users')->with('error', 'Sorry, something went wrong.');
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
        // If user is operator.
        if (PagesController::hasAccess(1))
        {
            // Get user, check exists.
            $user = User::find($id);
            if (!is_null($user))
            {
                // Default values to their original.
                $empID = $request->input('empID') ?? $user->employee_id;
                $forename = $request->input('firstName') ?? $user->forename;
                $surname = $request->input('lastName') ?? $user->surname;
                $phone = $request->input('phone') ?? $user->phone_number;

                // Information about where the submission came from, and the job are needed.
                $this->validate($request, [
                    'isCaller' => 'required',
                    'job-select' => 'required'
                ]);
                // Check job supplied is valid.
                $job = Job::find($request->input('job-select'));
                if (is_null($job))
                {
                    return redirect()->back();
                }
                // Caller account.
                if ($request->input('isCaller') == 'true')
                {
                    // Callers need a department
                    $this->validate($request, [
                        'department-select' => 'required',
                    ]);
                    // Check if other user already has given employeeID
                    $result = User::where('employee_id', $empID)->where('id', '!=', $id)->get();
                    if (count($result) ==  0)
                    {
                        $user->employee_id = $empID;
                        $user->forename = $forename;
                        $user->surname = $surname;
                        $user->job_id = $job;
                        $user->phone_number = $phone;
                        $user->save();

                        return redirect("/users/$id")->with('success', 'Caller Info Updated');
                    }

                    $data = array(
                        'error'=>'Duplicate Employee ID',
                        'search'=>$empID
                    );

                    return redirect("/users/$id")->with($data);
                }

                // System account.
                elseif ($request->input('isCaller') == 'false')
                {
                    // Default values to their original.
                    $username = $request->input('username') ?? $user->username;
                    $password = $request->input('password') ?? $user->password;
                    // Check if other user already has given employeeID
                    $result = User::where('employee_id', $empID)->where('id', '!=', $id)->get();
                    if (count($result) ==  0)
                    {
                        $user->username = $username;
                        $user->password = $password;
                         $user->employee_id = $empID;
                        $user->forename = $forename;
                        $user->surname = $surname;
                        $user->job_id = $job;
                        $user->phone_number = $phone;
                        $user->save();

                        return redirect("/users/$id")->with('success', 'System Account Info Updated');
                    }
                    $data = array(
                        'error'=>'Duplicate Employee ID',
                        'search'=>$request->input('empID')
                    );
                    return redirect("/users/$id")->with($data);
                }
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
            // Remove information only specific to this user.
            $user = User::find($id);
            $user->delete();

            $speciality = Speciality::where('specialist_id', '=', $id)->delete();
            $skills = Skill::where('specialist_id', $id)->delete();

            // For information where this user is related, replace with 0, indicating no user has been set for this task; rather than causing an error or having the join fail.
            $calls = Call::where('caller_id', $id)->get();
            foreach ($calls as $call) {
                $call->caller_id = 0;
                $call->save();
            }

            $problem_logged = Problem::where('logged_by', $id)->get();
            foreach ($problem_logged as $problem) {
                $problem->logged_by = 0;
                $problem->save();
            }
            $problem_assigned = Problem::where('assigned_to', $id)->get();
            foreach ($problem_assigned as $problem) {
                $problem->assigned_to = 0;
                $problem->save();
            }
            $solved = ResolvedProblem::where('solved_by', $id)->get();
            foreach ($solved as $problem) {
                $problem->solved_by = 0;
                $problem->save();
            }
            $reassignments = Reassignments::where('specialist_id', $id)->get();
            foreach ($reassignments as $re) {
                $re->specialist_id = 0;
                $re->save();
            }
            $reassignments2 = Reassignments::where('reassigned_to', $id)->get();
            foreach ($reassignments2 as $re) {
                $re->reassigned_to = 0;
                $re->save();
            }

            return redirect('/users')->with('success', 'Account Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
