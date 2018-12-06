<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Problem;
use App\User;
use App\Job;

class ProblemController extends Controller
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

        if (!is_null($result) && count($result) != 0)
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
            // Get intial caller for problem.
            $problems = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problems.problem_type, problems.description, users.forename, users.surname, calls.id as cID
                FROM problems
                JOIN calls
                ON (
                    problems.id = calls.problem_id
                    AND calls.created_at = (
                        SELECT MIN(created_at)
                        FROM calls
                        WHERE problem_id = problems.id
                    )
                )
                JOIN users
                ON users.id = calls.caller_id'
            ));
                
            $resolved = Problem::join('resolved_problems', 'problems.id', '=', 'resolved_problems.problem_id')->select('resolved_problems.problem_id')->get();

            $data = array(
                'title' => "Problem Viewer",
                'desc' => "Displays all problems.",
                'problems' => $problems,
                'resolved' => $resolved,
                'links' => $this->operator_links,
                'active' => 'Problems'
            );

            return view('problems.index')->with($data);
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
            $header_types = DB::table('problem_types')->select('problem_types.*')->where('problem_types.parent', '=', '-1')->get();

            $types = DB::table('problem_types')->select('problem_types.*')->where('problem_types.parent', '!=', '-1')->get();

            $data = array(
                'title' => "Problem Creator",
                'desc' => "Create a New Problem",
                'header_types' => $header_types,
                'types' => $types,
                'links' => $this->operator_links,
                'active' => 'Problems'
            );

            return view('problems.create')->with($data);
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
        //
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
            $problem = Problem::find($id);

            $callers = DB::table('problems')->join('calls', 'problems.id', '=', 'calls.problem_id')->join('users', 'users.id', '=', 'calls.caller_id')->select('calls.id as cID', 'calls.notes', 'calls.created_at as cAT', 'users.*')->where('problems.id', '=', $id)->get();

            $assigned = DB::table('problems')->join('users', 'problems.assigned_to', '=', 'users.id')->select('users.*')->where('problems.id', '=', $id)->get();

            $resolved = DB::table('problems')->join('resolved_problems', 'problems.id', '=', 'resolved_problems.problem_id')->select('resolved_problems.solution_notes')->where('problems.id', '=', $id)->get();

            if (!is_null($problem) && !is_null($callers))
            {
                $data = array(
                    'title' => "Problem Viewer.",
                    'desc' => "Shows information on a problem.",
                    'problem' => $problem,
                    'callers' => $callers,
                    'specialist' => $assigned,
                    'resolved' => $resolved,
                    'links' => $this->operator_links,
                    'active' => 'Problems'
                );
                return view('problems.show')->with($data);
            }
            return "Error completing that request.";
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_user_for_call($id)
    {
        if ($this->hasAccess(1))
        {
            $problem = Problem::find($id);
            $users = User::join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.title', 'departments.name')->get();

            if (!is_null($problem))
            {
                $data = array(
                    'title' => "Add Call to Problem.",
                    'desc' => "Select a user to create a call for.",
                    'problem' => $problem,
                    'users' => $users,
                    'links' => $this->operator_links,
                    'active' => 'Problems'
                );
                return view('problems.select_user_for_call')->with($data);
            }
            return "Error completing that request.";
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function add_call($id, $caller_id)
    {
        if ($this->hasAccess(1))
        {
            $problem = Problem::find($id);
            $user = User::find($caller_id);

            if (!is_null($problem) && !is_null($user))
            {
                $data = array(
                    'title' => "Create Call for Problem.",
                    'desc' => "Add a call from a user to a problem.",
                    'problem' => $problem,
                    'user' => $user,
                    'links' => $this->operator_links,
                    'active' => 'Problems'
                );
                return view('problems.add_call')->with($data);
            }
            return "Error completing that request.";
        }
        return redirect('login')->with('error', 'Please log in first.');

    }
/*
    public function select_user_for_call()
    {
        $users = User::join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.title', 'departments.name')->get();

        $data = array(
            'title' => "Create Call for Problem.",
            'desc' => "Select a user to create a call for.",
            'problem' => '',
            'users' => $users,
            'links' => $this->operator_links,
            'active' => 'Problems'
        );
        return view('problems.select_user_for_call')->with($data);
    }
*/
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
