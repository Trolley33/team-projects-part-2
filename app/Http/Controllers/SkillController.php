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
use App\Skill;

class SkillController extends Controller
{
    public function create ()
    {

    }

    public function store (Request $request)
    {

    }

    public function show ($id)
    {
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login', 'Please log in first.');
    	}
    	$viewer = PagesController::getCurrentUser();

    	$user = User::find($id);
    	if (is_null($user))
    	{
    		return redirect()->back()->with('Sorry, something went wrong.');
    	}

    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
	    	$links = PagesController::getSpecialistLinks();
	    	$active = "Skills";
    	}
    	else
    	{
	    	$links = PagesController::getOperatorLinks();
	    	$active = "Users";
	    }


    	$skills = Skill::join('problem_types', 'skills.problem_type_id', '=', 'problem_types.id')->leftJoin('problem_types as parents', 'parents.id', '=', 'problem_types.parent')->where('specialist_id', $id)->selectRaw(
    		"skills.*, problem_types.id as ptID, problem_types.description as ptDesc, parents.id as parentID, parents.description as parentDesc"
    			)
    	->get();

    	$data = array(
    	    'title'=> "View Skills",
    	    'desc'=>"View & Modify Specialist Skills",
    	    'user'=>$user,
    	    'skills'=>$skills,
    	    'links'=>$links,
    	    'active'=>$active
    	);

    	return view('skills.show')->with($data);
    }

    public function show_compact ($id)
    {
    	
    }

    public function edit ($id, $skill_id)
    {

    }

    public function update (Request $request, $id, $skill_id)
    {

    }

    public function delete ($skill_id)
    {
    	
    }
}
