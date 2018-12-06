<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\User;
use App\Job;
use App\Department;
use App\Problem;
use App\Call;

class CallsController extends Controller
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
        //
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
                'title' => "Log New Call",
                'desc' => "Please select a problem to add to, or create a new problem.",
                'problems'=>$problems,
                'resolved'=>$resolved,
                'links' => $this->operator_links,
                'active' => 'Log New Call'
            );

            return view('calls.create')->with($data);
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
                'problem-id' => 'required',
                'user-id' => 'required',
                'notes' => 'required'
            ]);

            $newCall = new Call();
            $newCall->problem_id = $request->input('problem-id');
            $newCall->caller_id = $request->input('user-id');
            $newCall->notes = $request->input('notes');
            $newCall->save();

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Call Added');

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
            $call = Call::find($id);

            if (!is_null($call))
            {
                $user = User::find($call->caller_id);

                $problem = Problem::find($call->problem_id);

                $data = array(
                    'title' => "Call Viewer.",
                    'desc' => "View call information.",
                    'user' => $user,
                    'call' => $call,
                    'problem' => $problem,
                    'links' => $this->operator_links,
                    'active' => 'Problems'
                );

                return view('calls.show')->with($data);
            }
            return redirect('/problems')->with('error', 'Call not found.');
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
            $call = Call::find($id);

            if (!is_null($call))
            {
                $user = User::find($call->caller_id);

                $problem = Problem::find($call->problem_id);

                $data = array(
                    'title' => "Call Editor.",
                    'desc' => "Edit Call Info.",
                    'user' => $user,
                    'call' => $call,
                    'problem' => $problem,
                    'links' => $this->operator_links,
                    'active' => 'Problems'
                );

                return view('calls.edit')->with($data);
            }
            return redirect('/problems')->with('error', 'Call not found.');
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
                'notes' => 'required'
            ]);

            $call = Call::find($id);
            $call->notes = $request->input('notes');
            $call->save();

            return redirect('/problems/'.$call->problem_id)->with('success', 'Call Updated');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

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
            $call = Call::find($id);
            $problem = Problem::find($call->problem_id);
            $call->delete();

            return redirect('/problems/'.$problem->id)->with('success', 'Call Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
