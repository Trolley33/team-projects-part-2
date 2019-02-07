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
use App\ProblemType;
use App\Call;

class CallsController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Get all problems that are not solved, and all relevant information for displaying in table.
            $ongoing = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problem_types.description as problemType, problems.description, IFNULL(parents.description,0) as pDesc, problem_types.id as ptID, problems.importance, users.forename, users.surname, users.id as uID,calls.id as cID, importance.text, importance.class, importance.level
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
                ON users.id = calls.caller_id
                JOIN problem_types
                ON problem_types.id = problems.problem_type
                JOIN importance
                ON importance.id = problems.importance
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                LEFT JOIN resolved_problems rp ON rp.problem_id = problems.id
                WHERE rp.problem_id IS NULL'
            ));
            
            // Supply data to view.
            $data = array(
                'title' => "Log New Call",
                'desc' => "Please select a problem to add to, or create a new problem.",
                'ongoing'=>$ongoing,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Problems'
            );

            return view('calls.create')->with($data);
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {            
            // Validate that all data required has been submitted.
            $this->validate($request, [
                'problem-id' => 'required',
                'user-id' => 'required',
                'notes' => 'required'
            ]);

            // Create a new call object with the data submitted.
            $newCall = new Call();
            $newCall->problem_id = $request->input('problem-id');
            $newCall->caller_id = $request->input('user-id');
            $newCall->notes = $request->input('notes');
            // Save new call to database.
            $newCall->save();
            // Send user to the problem the call is associated with.
            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Call Added');

        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Find the call using the ID supplied.
            $call = Call::find($id);
            // If a valid call is found.
            if (!is_null($call))
            {
                // Gather information about call from other tables.
                $user = User::find($call->caller_id);
                $problem = Problem::find($call->problem_id);
                $type = ProblemType::find($problem->problem_type);
                // Grab initial call ID, so we can compare it to the selected call. (Can't delete initial call).
                $first_call = Call::where('problem_id', '=', $problem->id)->orderBy('created_at')->first();

                // Supply data to view.
                $data = array(
                    'title' => "Call Viewer.",
                    'desc' => "View call information.",
                    'user' => $user,
                    'call' => $call,
                    'first_call' => $first_call,
                    'problem' => $problem,
                    'problem_type' => $type,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('calls.show')->with($data);
            }
            // If no call found, redirect to problem page.
            return redirect('/problems')->with('error', 'Call not found.');
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Find the call using the ID supplied.
            $call = Call::find($id);
            // If a valid call is found.
            if (!is_null($call))
            {
                // Gather relevant information about call from other 
                $user = User::find($call->caller_id);
                $problem = Problem::find($call->problem_id);

                // Grab initial call ID, so we can compare it to the selected call. (Can't edit/delete initial call).
                $first_call = Call::where('problem_id', '=', $problem->id)->orderBy('created_at')->first();
                if ($first_call->id == $id)
                {
                    return redirect('/problems/'.$problem->id)->with('error', 'Cannot edit initial call.');
                }
                // Supply data to view.
                $data = array(
                    'title' => "Call Editor.",
                    'desc' => "Edit Call Info.",
                    'user' => $user,
                    'call' => $call,
                    'problem' => $problem,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('calls.edit')->with($data);
            }
            // If no call found, redirect to problem page.
            return redirect('/problems')->with('error', 'Call not found.');
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {            
            // Validate that all the data required has been submitted.
            $this->validate($request, [
                'notes' => 'required'
            ]);

            // Find the call using the ID supplied.
            $call = Call::find($id);
            if (!is_null($call))
            {
                // Change the relevant information and save it back to the database.
                $call->notes = $request->input('notes');
                $call->save();

                return redirect('/problems/'.$call->problem_id)->with('success', 'Call Updated');
            }
            // If no call found, redirect to problem page.
            return redirect('/problems')->with('error', 'Call not found.');
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Find call using ID.
            $call = Call::find($id);
            // Grab the problem the call is associated with.
            $problem = Problem::find($call->problem_id);
            $call->delete();

            // Redirect to the problem the call was linked to.
            return redirect('/problems/'.$problem->id)->with('success', 'Call Deleted');
        }
        // No access redirects to login page.
        return redirect('login')->with('error', 'Please log in first.');
    }
}
