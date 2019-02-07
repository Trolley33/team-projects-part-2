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
	public function index ()
	{
		if (!(PagesController::hasAccess(2)))
		{
			return redirect()->back();
		}
		$viewer = PagesController::getCurrentUser();
		return redirect("/skills/".$viewer->id);
	}
	
    public function create ($id)
    {
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	$viewer = PagesController::getCurrentUser();

    	$user = User::find($id);
    	if (is_null($user))
    	{
    		return redirect()->back();
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

	    $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->select('problem_types.*','parents.id as pID', 'parents.description as pDesc')->get();

    	$data = array(
    	    'title'=> "View Skills",
    	    'desc'=>"View & Modify Specialist Skills",
    	    'user'=>$user,
    	    'problem_types'=>$problem_types,
    	    'links'=>$links,
    	    'active'=>$active
    	);

    	return view('skills.create')->with($data);
    }

    public function add_ability ($id, $ptID)
    {
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	$viewer = PagesController::getCurrentUser();

    	$user = User::find($id);
    	$problem_type = ProblemType::find($ptID);

    	if (is_null($user) || is_null($problem_type))
    	{
    		return redirect()->back();
    	}
    	$parent = ProblemType::find($problem_type->parent);

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


    	$data = array(
    	    'title'=> "View Skills",
    	    'desc'=>"View & Modify Specialist Skills",
    	    'user'=>$user,
    	    'problem_type'=>$problem_type,
    	    'parent'=>$parent,
    	    'links'=>$links,
    	    'active'=>$active
    	);

    	return view('skills.add_ability')->with($data);
    }

    public function store (Request $request, $id)
    {
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}

    	$this->validate($request, [
    		'problem_type'=>'required',
    		'ability'=>'required'
    	]);

    	$ptID = $request->input('problem_type');
    	$problem_type = ProblemType::find($ptID);

    	$user = User::find($id);
    	$viewer = PagesController::getCurrentUser();

    	if (is_null($user) || is_null($problem_type))
    	{
    		return redirect()->back();
    	}

    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
	    }

	    $result = Skill::where('specialist_id', $id)->where('problem_type_id', $ptID)->get();

	    if (count($result) > 0)
	    {
	    	return redirect("/skills/$id")->with('error', 'Skill Already Exists for this Problem Type.');
	    }

	    $skill = new Skill();
	    $skill->specialist_id = $id;
	    $skill->problem_type_id = $ptID;
	    $skill->ability = $request->input('ability');
	    $skill->save();

    	return redirect("/skills/$id")->with('success', 'Skill Added.');
    }

    public function show ($id)
    {
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
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
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
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
    	}


    	$skills = Skill::join('problem_types', 'skills.problem_type_id', '=', 'problem_types.id')->leftJoin('problem_types as parents', 'parents.id', '=', 'problem_types.parent')->where('specialist_id', $id)->selectRaw(
    		"skills.*, problem_types.id as ptID, problem_types.description as ptDesc, parents.id as parentID, parents.description as parentDesc"
    			)
    	->get();

    	$data = array(
    	    'user'=>$user,
    	    'skills'=>$skills,
    	);

    	return view('skills.show_compact')->with($data);
    }

    public function edit ($id, $skill_id)
    {

    }

    public function update (Request $request, $id, $skill_id)
    {

    }

    public function delete ($skill_id)
    {
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}

    	$skill = Skill::find($skill_id);

    	if (is_null($skill))
    	{
    		return redirect()->back();
    	}

    	$user = User::find($skill->specialist_id);
    	$viewer = PagesController::getCurrentUser();

    	if (is_null($user))
    	{
    		return redirect()->back();
    	}

    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
	    }

	    $skill->delete();

    	return redirect("/skills/".$user->id)->with('success', 'Skill Deleted.');
    }
}
