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
    /**
     * Redirects user to their skill page, if applicable.
     */
	public function index ()
	{
        // Non-specialists 'skill' pages are undefined, so redirect.
		if (!(PagesController::hasAccess(2)))
		{
			return redirect()->back();
		}
        // Get viewing specialist's ID, and redirect to their skills page.
		$viewer = PagesController::getCurrentUser();
		return redirect("/skills/".$viewer->id);
	}

    /**
     * Selects problem type to create skill for.
     */
    public function create ($id)
    {
        // If user is not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
        // Get user viewing page.
    	$viewer = PagesController::getCurrentUser();
        // Check ID is valid.
    	$user = User::find($id);
    	if (is_null($user))
    	{
    		return redirect()->back();
    	}

        // If user is specialist, they can only access their own page.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
            // Set links to be different for who is logged in.
	    	$links = PagesController::getSpecialistLinks();
	    	$active = "Skills";
    	}
    	else
    	{
	    	$links = PagesController::getOperatorLinks();
	    	$active = "Users";
	    }

        // Get all problem types to choose from, excluding ones that are already skills.
	    $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->select('problem_types.*','parents.id as pID', 'parents.description as pDesc')
            ->leftJoin('skills', function ($join) use($user) {
                $join->on('skills.problem_type_id', '=', 'problem_types.id')->where('skills.specialist_id', '=', $user->id);
            })
            ->whereNull('skills.id')->get();

    	$data = array(
    	    'title'=> "Create Skill",
    	    'desc'=>"View & Modify Specialist Skills",
    	    'user'=>$user,
    	    'problem_types'=>$problem_types,
    	    'links'=>$links,
    	    'active'=>$active
    	);

    	return view('skills.create')->with($data);
    }

    /**
     * Chooses ability level for skill.
     */
    public function add_ability ($id, $ptID)
    {
        // If user is not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	// Get user viewing page.
        $viewer = PagesController::getCurrentUser();
        // Check IDs are valid.
        $user = User::find($id);
    	$problem_type = ProblemType::find($ptID);

    	if (is_null($user) || is_null($problem_type))
    	{
    		return redirect()->back();
    	}
    	$parent = ProblemType::find($problem_type->parent);

        // Specialist can only view their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
            // Set links depending on who is logged in
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

    /**
     * Chooses new ability level for skill.
     */
    public function edit_ability ($id, $skillID, $ptID)
    {
        // If user is not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	// Get user viewing page.
        $viewer = PagesController::getCurrentUser();
        // Check IDs are valid.
        $user = User::find($id);
    	$problem_type = ProblemType::find($ptID);
    	$skill = Skill::find($skillID);

    	if (is_null($user) || is_null($problem_type) || is_null($skill))
    	{
    		return redirect()->back();
    	}
    	$parent = ProblemType::find($problem_type->parent);

        // Specialist can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
            // Set links depending on who is logged in.
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
    	    'skill'=>$skill,
    	    'links'=>$links,
    	    'active'=>$active
    	);
    	return view('skills.edit_ability')->with($data);
    }

    /**
     * Adds skill to database.
     */
    public function store (Request $request, $id)
    {
        // If user is not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}

        // Check that required fields have been supplied.
    	$this->validate($request, [
    		'problem_type'=>'required',
    		'ability'=>'required'
    	]);

        // Validate the fields/ID.
    	$ptID = $request->input('problem_type');
    	$problem_type = ProblemType::find($ptID);
    	$user = User::find($id);
    	$viewer = PagesController::getCurrentUser();

    	if (is_null($user) || is_null($problem_type))
    	{
    		return redirect()->back();
    	}

        // Specialist can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
	    }
        // Check a skill doesn't already exist for this problem type.
	    $result = Skill::where('specialist_id', $id)->where('problem_type_id', $ptID)->get();
	    if (count($result) > 0)
	    {
	    	return redirect("/skills/$id")->with('error', 'Skill Already Exists for this Problem Type.');
	    }

        // Check ability between specified range.
	    $ability = $request->input('ability');

	    if ($ability > 10 || $ability < 0)
    	{
			return redirect("/skills/$id")->with('error', 'Invalid Ability Supplied.');
    	}
        // Create and save new skill.
	    $skill = new Skill();
	    $skill->specialist_id = $id;
	    $skill->problem_type_id = $ptID;
	    $skill->ability = $ability;
	    $skill->save();

    	return redirect("/skills/$id")->with('success', 'Skill Added.');
    }

    /**
     * Shows all skills for user.
     */
    public function show ($id)
    {
        // If user not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	$viewer = PagesController::getCurrentUser();
        // Get user and check exists.
    	$user = User::find($id);
    	if (is_null($user))
    	{
    		return redirect()->back()->with('Sorry, something went wrong.');
    	}
        // Specialist can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
            // Set links based on viewer.
	    	$links = PagesController::getSpecialistLinks();
	    	$active = "Skills";
    	}
    	else
    	{
	    	$links = PagesController::getOperatorLinks();
	    	$active = "Users";
	    }

        // Get list of all skills for user.
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

    /**
     * Shows all skills for user, in compacted modal friendly view.
     */
    public function show_compact ($id)
    {
        // If user not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	$viewer = PagesController::getCurrentUser();
        // Get user and check exists.
    	$user = User::find($id);
    	if (is_null($user))
    	{
    		return redirect()->back()->with('Sorry, something went wrong.');
    	}

        // Specialist can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
    	}
        // Get list of all skills for user.
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

    /**
     * Selects new problem type for skill.
     */
    public function edit ($id, $skill_id)
    {
        // If user is not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
    	$viewer = PagesController::getCurrentUser();
        // Get user and check exists.
    	$user = User::find($id);
    	if (is_null($user))
    	{
    		return redirect()->back();
    	}
        // Specialists can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
            // Set links based on viewer.
	    	$links = PagesController::getSpecialistLinks();
	    	$active = "Skills";
    	}
    	else
    	{
	    	$links = PagesController::getOperatorLinks();
	    	$active = "Users";
	    }
        // Get list of problem types.
	    $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->select('problem_types.*','parents.id as pID', 'parents.description as pDesc')->get();

        // Get current skill.
	    $skill = Skill::find($skill_id);

    	$data = array(
    	    'title'=> "Edit Skill",
    	    'desc'=>"View & Modify Specialist Skills",
    	    'user'=>$user,
    	    'problem_types'=>$problem_types,
    	    'skill'=>$skill,
    	    'links'=>$links,
    	    'active'=>$active
    	);

    	return view('skills.edit')->with($data);
    }

    /**
     * Modifies database entry for skill.
     */
    public function update (Request $request, $skill_id)
    {
        // If user is not specialsit or operator, redirect.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
        // Get skill and check exists.
    	$skill = Skill::find($skill_id);
    	if (is_null($skill))
    	{
    		return redirect()->back();
    	}

        // Get user for found skill.
    	$user = User::find($skill->specialist_id);
    	$viewer = PagesController::getCurrentUser();
        // Specialist can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
	    }

        // If left blank, default to already set values.
    	$ptID = $request->input('problem_type') ?? $skill->problem_type_id;
    	$ability = $request->input('ability') ?? $skill->ability;

        // Check ability is a number.
        if (is_numeric($ability))
        {
            return redirect("/skills/".$user->id)->with('error', 'Invalid Ability Supplied.');
        }
        // Valid range 0-10.
    	if ($ability > 10 || $ability < 0)
    	{
    		return redirect("/skills/".$user->id)->with('error', 'Invalid Ability Supplied.');
    	}
        // Check newly select problem type is not already a skill, unless it's this one.
	    $result = Skill::where('specialist_id', $user->id)->where('problem_type_id', $ptID)->where('id', '!=', $skill->id)->get();
	    if (count($result) > 0)
	    {
	    	return redirect("/skills/".$user->id)->with('error', 'Skill Already Exists for this Problem Type.');
	    }

        // Update skill.
	    $skill->problem_type_id = $ptID;
	    $skill->ability = $request->input('ability');
	    $skill->save();

    	return redirect("/skills/".$user->id)->with('success', 'Skill Updated.');
    }

    /**
     * Removes skill from specialist.
     */
    public function delete ($skill_id)
    {
        // If user is not specialist or operator, redirect them.
    	if (!(PagesController::hasAccess(2) || PagesController::hasAccess(1)))
    	{
    		return redirect('/login')->with('error', 'Please log in first.');
    	}
        // Get skill and check exists.
    	$skill = Skill::find($skill_id);
    	if (is_null($skill))
    	{
    		return redirect()->back();
    	}

        // Get user of skill selected.
    	$user = User::find($skill->specialist_id);
    	$viewer = PagesController::getCurrentUser();
    	if (is_null($user))
    	{
    		return redirect()->back();
    	}
        // Specialists can only access their own skills.
    	if (PagesController::hasAccess(2))
    	{
    		if ($user->id != $viewer->id)
    		{
	    		return redirect()->back();
	    	}
	    }
        // Delete skill from database.
	    $skill->delete();

    	return redirect("/skills/".$user->id)->with('success', 'Skill Deleted.');
    }
}
