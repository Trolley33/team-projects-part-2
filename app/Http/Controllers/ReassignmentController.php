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

    public function request_reassignment(Request $request, $id)
    {
        if (PagesController::hasAccess(2))
        {
            $this->validate($request, [
                'reason' => 'required'
            ]);

            $problem = Problem::find($id);

            $result = Reassignments::where('problem_id', '=', $id)->first();
            if (is_null($result))
            {
                $assign = new Reassignments();
                $assign->problem_id = $problem->id;
                $assign->specialist_id = $problem->assigned_to;
                $assign->reason = $request->input('reason');
                $assign->save();

                $problem->assigned_to = 0;
                $problem->save();

                return redirect('/specialist')->with('success', 'Problem reassigned.');
            }
            return redirect('/specialist')->with('error', 'Sorry, that request failed, please try again later.');
        }

        return redirect('login')->with('error', 'Please log in first.');
    }
}
