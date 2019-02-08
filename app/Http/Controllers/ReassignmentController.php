<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Problem;
use App\ProblemType;
use App\ResolvedProblem;
use App\Call;
use App\User;
use App\Job;
use App\Equipment;
use App\Software;
use App\Importance;
use App\AffectedHardware;
use App\AffectedSoftware;
use App\Reassignments;

class ReassignmentController extends Controller
{
    /**
     * Modifies database so problem must be reassigned, and specialist can no longer view it.
     */
    public function request_reassignment(Request $request, $id)
    {
        // If user has specialist access.
        if (PagesController::hasAccess(2))
        {
            // Check reason has been supplied.
            $this->validate($request, [
                'reason' => 'required'
            ]);

            // Get problem object from ID, and check it exists.
            $problem = Problem::find($id);
            if (is_null($problem))
            {
                return redirect('/specialist')->with('success', 'Sorry, something went wrong.');
            }

            // Create new reassignment entry.
            $assign = new Reassignments();
            $assign->problem_id = $problem->id;
            $assign->specialist_id = $problem->assigned_to;
            $assign->reason = $request->input('reason');
            // Not reassigned to anyone yet.
            $assign->reassigned_to = 0;
            $assign->save();

            // No longer assigned to anyone.
            $problem->assigned_to = 0;
            $problem->save();

            return redirect('/specialist')->with('success', 'Problem reassigned.');
        }

        return redirect('login')->with('error', 'Please log in first.');
    }
}
