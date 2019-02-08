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

class ProblemController extends Controller
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
            // Get a list of unresolved problems, with a range of related information about each problem; for displaying in table.
            $ongoing = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problem_types.description as ptDesc, problem_types.id as ptID, problems.description, IFNULL(parents.description,0) as pDesc, problems.importance, users.forename, users.surname, users.id as uID, calls.id as cID, IFNULL(specialists.forename,0) as sForename, IFNULL(specialists.surname,0) as sSurname, IFNULL(specialists.id,0) as sID, importance.text, importance.class, importance.level
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
                LEFT JOIN users specialists
                ON specialists.id = problems.assigned_to
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                LEFT JOIN resolved_problems rp ON rp.problem_id = problems.id
                WHERE rp.problem_id IS NULL'
            ));

            // Get a list of resolved problems, with a range of related information about each problem; for displaying in table.
            $resolved = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problem_types.description as ptDesc, problem_types.id as ptID, problems.description, IFNULL(parents.description,0) as pDesc, problems.importance, users.forename, users.surname, users.surname, users.id as uID, calls.id as cID, IFNULL(specialists.forename,0) as sForename, IFNULL(specialists.surname,0) as sSurname, IFNULL(specialists.id,0) as sID, rp.created_at as solved_at
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
                LEFT JOIN users specialists
                ON specialists.id = problems.assigned_to
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                JOIN resolved_problems rp ON rp.problem_id = problems.id'
            ));
            // Supply data to view.
            $data = array(
                'title' => "Problem Viewer",
                'desc' => "Displays all problems.",
                'ongoing' => $ongoing,
                'resolved' => $resolved,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Problems'
            );
            // Show view.
            return view('problems.index')->with($data);
        }
        // Otherwise, if user is specialist.
        elseif (PagesController::hasAccess(2)) 
        {
            // Get the currently logged in specialist's account information.
            $specialist = PagesController::getCurrentUser();

            // Get all information on problems assigned to this specialist, which haven't been solved yet.
            $ongoing = DB::select(DB::raw(
                "SELECT problems.id as id, problems.created_at, problem_types.description as ptDesc, problems.description, problems.assigned_to, problems.importance, IFNULL(parents.description,0) as pDesc, users.forename, users.surname, users.id as uID, calls.id as cID, importance.text, importance.class, importance.level
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
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                JOIN importance
                ON importance.id = problems.importance
                LEFT JOIN resolved_problems
                ON resolved_problems.problem_id = problems.id
                WHERE resolved_problems.id IS NULL AND problems.assigned_to = ".$specialist->id.";"
            ));

            // Get all information on problems assigned to this specialist, which have already been solved.
            $resolved = DB::select(DB::raw(
                "SELECT problems.id as id, problems.created_at, problems.updated_at, problem_types.description as ptDesc, problems.description, problems.assigned_to, problems.importance, IFNULL(parents.description,0) as pDesc, users.forename, users.surname, users.id as uID, calls.id as cID
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
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                JOIN importance
                ON importance.id = problems.importance
                JOIN resolved_problems
                ON resolved_problems.problem_id = problems.id
                WHERE problems.assigned_to = ".$specialist->id.";"
            ));
            // Supply data to view.
            $data = array(
                'title' => "Specialist Homepage",
                'desc' => "Please select a task.",
                'user' => $specialist,
                'ongoing' => $ongoing,
                'resolved' => $resolved,
                'links' => PagesController::getSpecialistLinks(),
                'active' => 'Problems'
            );
            return view('pages.specialist.problems')->with($data);
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
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get list of all users, who could potentially be creating a call.
            $users = User::join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.title', 'departments.name')->get();
            // Supply data to view.
            $data = array(
                'title' => "Problem Creator",
                'desc' => "Create a New Problem",
                'users' => $users,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Problems'
            );

            return view('problems.select_user_for_problem')->with($data);
        }
        // No access redirects to login page.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /** 
     * Selects problem type for problem.
     * Follows from @create.
     */
    public function select_problem_type($user_id)
    {
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get list of all problem types, including their parents (if applicable), for selection.
            $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('problem_types.*, IFNULL(parents.description,0) as parent_description')->get();
            
            // User ID previously supplied from @create must be valid.
            $user = User::find($user_id);
            if (!is_null($user))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Create Problem",
                    'desc' => " ",
                    'user'=>$user,
                    'problem_types'=>$problem_types,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('problems.select_problem_type_for_problem')->with($data);
    	    }
    	    return redirect('/problems/create')->with('error', 'Invalid user selected.');
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }

    /** 
     * Adds notes & details to problem.
     * Follows from @select_problem_type.
     */
    public function add_problem_details($user_id, $problem_type_id)
    {
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get all supplied info as eloquent objects (easier to use, and checks if they're in the database).
            $pt = ProblemType::find($problem_type_id);
            $parent = ProblemType::find($pt->parent);
            $user = User::find($user_id);
            $importance = Importance::orderBy('level')->get();
            if (!is_null($pt) && !is_null($user) && !is_null($importance))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Create Problem",
                    'desc' => " ",
                    'user'=>$user,
                    'importance'=>$importance,
                    'problem_type'=>$pt,
                    'parent'=>$parent,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('problems.add_problem_details')->with($data);
            }

            return redirect('/problems/create')->with('error', 'Invalid User/Problem Type/Importance selected.');
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }

    /** 
     * Selects specialist/operator to assign to problem.
     * Follows from @add_problem_details.
     */
    public function select_specialist_for_problem(Request $request, $user_id, $problem_type_id)
    {
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Check required data has been supplied.
            $this->validate($request, [
                'desc' => 'required',
                'notes' => 'required',
                'importance' => 'required',
            ]);

             // Get all supplied info as eloquent objects (easier to use, and checks if they're in the database).
            $user = User::find($user_id);
            $problem_type = ProblemType::find($problem_type_id);
            $parent = ProblemType::find($problem_type->parent);
            
            if (!is_null($user) && !is_null($problem_type))
            {
                // Get all information about specialists, raw query used due to complexity.
                $specialists = DB::select(DB::raw(
                    "SELECT speciality.id as sID, problem_types.id as pID, problem_types.description, IFNULL(parents.description,0) as parent_description, problem_types.parent, IFNULL(COUNT(problems.id) - COUNT(resolved_problems.id), 0) as jobs, timeoff.startDate, users.*,
                        GROUP_CONCAT(skill_types.description) as skills_list
                    FROM users
                    JOIN speciality
                    ON users.id = speciality.specialist_id
                    JOIN problem_types
                    ON speciality.problem_type_id = problem_types.id
                    LEFT JOIN problem_types as parents
                    ON problem_types.parent = parents.id
                    LEFT JOIN problems
                    ON problems.assigned_to = users.id
                    LEFT JOIN resolved_problems
                    ON resolved_problems.problem_id = problems.id
                    LEFT JOIN timeoff
                    ON (timeoff.user_id = users.id AND 
                        timeoff.id = (
                            SELECT timeoff.id FROM timeoff
                            ORDER BY timeoff.created_at desc
                            LIMIT 1)
                        )
                    LEFT JOIN skills
                    ON users.id = skills.specialist_id
                    LEFT JOIN problem_types as skill_types
                    ON skills.problem_type_id = skill_types.id
                    WHERE
                        (DATE(NOW()) NOT BETWEEN timeoff.startDate AND timeoff.endDate)
                        OR timeoff.id IS NULL
                    GROUP BY users.id, speciality.id, timeoff.startDate
                        "
                ));
                // Supply data to view.
                $data = array(
                    'title' => "Edit Assigned Specialist",
                    'desc' => "",
                    'problem_description'=>$request->input('desc'),
                    'problem_notes'=>$request->input('notes'),
                    'problem_importance'=>$request->input('importance'),
                    'user'=>$user,
                    'parent'=>$parent,
                    'problem_type'=>$problem_type,
                    'specialists'=>$specialists,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );
                return view('problems.select_specialist_for_problem')->with($data);
            }

            return redirect('/problems/create')->with('error', 'Invalid User/Problem Type selected.');
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
            // Check all required data is supplied.
            $this->validate($request, [
                'desc' => 'required',
                'notes' => 'required',
                'importance' => 'required',
                'user_id' => 'required',
                'problem_type_id' => 'required',
                'submit' =>'required'
            ]);

            // Get operator logging this problem.
            $operator = PagesController::getCurrentUser();

            // Get objects from inputs, an validate their existence.
            $pt = ProblemType::find($request->input('problem_type_id'));
            $user = User::find($request->input('user_id'));
            $importance = Importance::where('level', $request->input('importance'))->first();
            if (is_null($pt) || is_null($user) || is_null($importance))
            {
                return redirect('/problems/')->with('error', 'Invalid problem type, user, or importance level supplied.');
            }

            // Create new problem object and append info.
            $problem = new Problem();
            $problem->description = $request->input('desc');
            $problem->notes = $request->input('notes');
            $problem->problem_type = $pt->id;
            $problem->logged_by = $operator->id;
            $problem->importance = $importance->level;

            // Assign Problem to Current Operator.
            if ($request->input('submit') == "Assign Problem to You")
            {
                $problem->assigned_to = $operator->id;
            }

            // Assign Problem to Selected Specialist.
            else if ($request->input('submit') == "Assign Specialist")
            {
                // Check a specialist was chosen, and that the specialist is valid.
                $this->validate($request, [
                    'specialist' => 'required'
                ]);

                $specialist = User::find($request->input('specialist'));
                if (!is_null($specialist))
                {
                    return redirect('/problems/')->with('error', 'Invalid specialist selected.');
                }
                $problem->assigned_to = $specialist->id;
            }
            else
            {
                return redirect('/problems/')->with('error', 'Invalid form data supplied.');
            }

            $problem->save();

            // Create a default call for problem.
            $call = new Call();
            $call->problem_id = $problem->id;
            $call->caller_id = $user->id;
            $call->notes = "Initial call.";
            $call->save();

            return redirect('/problems/'.$problem->id)->with('success', 'Problem Created');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // If problem in URL exists.
        $problem = Problem::find($id);
        if (!is_null($problem))
        {
            // If viewer is operator or specialist.
            if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
            {
                // Set links for navbar depending on person viewing page.
                if (PagesController::hasAccess(1))
                {
                    $links = PagesController::getOperatorLinks();
                }
                if (PagesController::hasAccess(2))
                {
                    $links = PagesController::getSpecialistLinks();
                }
                // Get problem type information
                $type = ProblemType::find($problem->problem_type);

                $parent = ProblemType::find($type->parent);

                /* -- Grab all other relevant problem information -- */
                $callers = DB::table('problems')->join('calls', 'problems.id', '=', 'calls.problem_id')->join('users', 'users.id', '=', 'calls.caller_id')->select('calls.id as cID', 'calls.notes', 'calls.created_at as cAT', 'users.*')->where('problems.id', '=', $id)->get();

                $assigned = User::join('problems', 'problems.assigned_to', '=', 'users.id')->where('problems.id', '=', $id)->select('users.*')->first();

                $resolved = Problem::join('resolved_problems', 'resolved_problems.problem_id', '=', 'problems.id')->select('resolved_problems.solution_notes', 'resolved_problems.created_at')->where('problems.id', '=', $id)->get()->first();

                $hardware = Equipment::join('affected_hardware', 'affected_hardware.equipment_id', '=', 'equipment.id')->join('problems', 'problems.id', '=', 'affected_hardware.problem_id')->where('problems.id', '=', $id)->select('equipment.*')->get();

                $software = Software::join('affected_software', 'affected_software.software_id', '=', 'software.id')->join('problems', 'problems.id', '=', 'affected_software.problem_id')->where('problems.id', '=', $id)->select('software.*')->get();

                $reassignments = Reassignments::join('problems', 'problems.id', '=', 'reassignments.problem_id')->join('users', 'users.id', '=', 'reassignments.specialist_id')->select('users.id as uID', 'users.forename', 'users.surname', 'reassignments.reason', 'reassignments.created_at')->where('problems.id', '=', $id)->get();

                $importance = Importance::find($problem->importance);

                $operator = User::find($problem->logged_by);

                if (!is_null($callers))
                {
                    // Supply data to view.
                    $data = array(
                        'title' => "Problem Viewer.",
                        'desc' => "Shows information on a problem.",
                        'problem' => $problem,
                        'problem_type' => $type,
                        'parent' => $parent,
                        'callers' => $callers,
                        'specialist' => $assigned,
                        'resolved' => $resolved,
                        'hardware' => $hardware,
                        'software' => $software,
                        'reassignments'=>$reassignments,
                        'importance' => $importance,
                        'operator'=>$operator,
                        'links' => $links,
                        'active' => 'Problems'
                    );
                    // Change view based on viewer account.
                    if (PagesController::hasAccess(1))
                    {
                        return view('problems.show')->with($data);
                    }
                    elseif (PagesController::hasAccess(2))
                    {
                        return view('problems.show_specialist')->with($data);
                    }
                }
                // Redirect to allowed page depending on viewer.
                if (PagesController::hasAccess(1))
                {
                    return redirect('/problems')->with('error', 'Invalid/corrupted problem selected.');
                }
                elseif (PagesController::hasAccess(2))
                {
                    return redirect('/specialist')->with('error', 'Invalid/corrupted problem selected.');
                }
            }
            return redirect('login')->with('error', 'Please log in first.');
        }
        return redirect('/problems/')->with('error', 'Sorry, something went wrong.');
    }

    /** 
     * Selects user for new call, on previous problem.
     * Follows from Calls@create & Problems@show->append call.
     */
    public function select_user_for_call($id)
    {
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get problem object from URL.
            $problem = Problem::find($id);
            // Get list of users to choose from.
            $users = User::join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.title', 'departments.name')->get();

            // If problem exists.
            if (!is_null($problem))
            {
                // Get initial caller for reference.
                $caller = DB::select(DB::raw('
                    SELECT users.* FROM users
                    JOIN calls
                    ON calls.caller_id = users.id AND calls.created_at = (
                        SELECT MIN(created_at)
                        FROM calls
                        WHERE problem_id = '.$problem->id.'
                    );'))[0];
                // Supply data to view.
                $data = array(
                    'title' => "Add Call to Problem.",
                    'desc' => "Select a user to create a call for.",
                    'problem' => $problem,
                    'caller' => $caller,
                    'users' => $users,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );
                return view('problems.select_user_for_call')->with($data);
            }
            return redirect('/calls/create')->with('error', 'Invalid User or Problem Selected');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /** 
     * Adds call information for new call.
     * Follows from Calls@select_user_for_call.
     */
    public function add_call($id, $caller_id)
    {
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get problem & user object.
            $problem = Problem::find($id);
            $user = User::find($caller_id);

            // Check both exist.
            if (!is_null($problem) && !is_null($user))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Create Call for Problem.",
                    'desc' => "Add a call from a user to a problem.",
                    'problem' => $problem,
                    'user' => $user,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );
                return view('problems.add_call')->with($data);
            }
            return redirect('/calls/create')->with('error', 'Invalid Problem Selected');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function select_equipment_to_add($id)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Set links for navbar based on viewer's access.
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }

            // Get problem object from id.
            $problem = Problem::find($id);
            // Check exists.
            if (!is_null($problem))
            {
                // Get all currently affected hardware, and list of all equipment.
                $affected_hardware = AffectedHardware::where('problem_id', '=', $id)->get();
                $equipment = Equipment::all();

                // Supply data to view.
                $data = array(
                    'title' => "Add Affected Equipment to Problem.",
                    'desc' => "Select equipment affected by problem.",
                    'problem' => $problem,
                    'equipment' => $equipment,
                    'affected' => $affected_hardware,
                    'links' => $links,
                    'active' => 'Problems'
                );
                return view('problems.select_equipment_to_add')->with($data);
            }
            // Redirect to allowed page based on viewer access.
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Adds database entry(s) for affected_hardware.
     * Follows from ProblemController@select_equipment_to_add.
     */
    public function append_equipment(Request $request)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            
            // If all required data is supplied.
            $this->validate($request, [
                'problem-id' => 'required',
                'equipment' => 'required',
            ]);

            // Using information supplied as iterable, make affected_hardware entries.
            $equipments = $request->input('equipment');
            $problem_id = $request->input('problem-id');
            foreach ($equipments as $e)
            {
                // Check equipment is correct.
                if (!is_null(Equipment::find($e)))
                {
                    $a_h = new AffectedHardware();
                    $a_h->problem_id = $problem_id;
                    $a_h->equipment_id = $e;
                    $a_h->save();
                }
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Equipment Added');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Selects which equipment to remove from problem.
     * Follows from Problem@show->remove equipment.
     */
    public function select_equipment_to_remove($id)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Set links based on viewer access.
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            // Get problem object from ID.
            $problem = Problem::find($id);
            // Gget list of all currently affected hardware.
            $equipment = Equipment::join('affected_hardware', 'equipment.id', '=', 'affected_hardware.equipment_id')->where('affected_hardware.problem_id', '=', $id)->get();

            if (!is_null($problem))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Remove Equipment from Problem.",
                    'desc' => "Select a user to create a call for.",
                    'problem' => $problem,
                    'equipment' => $equipment,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.select_equipment_to_remove')->with($data);
            }
            // Redirect to allowed page based on viewer.
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Removes database entry(s) for affected_hardware
     * Follows from ProblemController@show->select_equipment_to_remove.
     */
    public function delete_equipment(Request $request)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {           
            // If all required data is supplied. 
            $this->validate($request, [
                'problem-id' => 'required',
                'equipment' => 'required',
            ]);

            // Using information supplied as iterable, delete affected_hardware entries.
            $affected = $request->input('equipment');
            $problem_id = $request->input('problem-id');
            foreach ($affected as $ah)
            {
                $a_h = AffectedHardware::find($ah);
                $a_h->delete();
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Equipment Removed');

        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Selects which software to append to problem.
     * Follows from Problem@show->append software.
     */
    public function select_software_to_add($id)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Set links for navbar based on viewer's access.
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            // Get problem object from id.
            $problem = Problem::find($id);


            if (!is_null($problem))
            {
                // Get all currently affected software, and list of all software.
                $affected_software = AffectedSoftware::where('problem_id', '=', $id)->get();
                $software = Software::all();
                
                // Supply data to view.
                $data = array(
                    'title' => "Add Software to Problem.",
                    'desc' => "",
                    'problem' => $problem,
                    'software' => $software,
                    'affected' => $affected_software,
                    'links' => $links,
                    'active' => 'Problems'
                );
                return view('problems.select_software_to_add')->with($data);
            }
            // Redirect to allowed page based on viewer access.
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Adds database entry(s) for affected_software.
     * Follows from ProblemController@select_software_to_add.
     */
    public function append_software(Request $request)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            
            // If all required data is supplied.
            $this->validate($request, [
                'problem-id' => 'required',
                'software' => 'required',
            ]);

            // Using information supplied as iterable, make affected_software entries.
            $softwares = $request->input('software');
            $problem_id = $request->input('problem-id');

            foreach ($softwares as $s)
            {
                // Check software is correct.
                if (!is_null(Equipment::find($e)))
                {
                    $a_s = new AffectedSoftware();
                    $a_s->problem_id = $problem_id;
                    $a_s->software_id = $s;
                    $a_s->save();
                }
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Software Added');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Selects which software to remove from problem.
     * Follows from Problem@show->remove software.
     */
    public function select_software_to_remove($id)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Set links based on viewer access.
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            // Get problem object from ID.
            $problem = Problem::find($id);
            // Get list of all currently affected software.
            $software = Software::join('affected_software', 'software.id', '=', 'affected_software.software_id')->where('affected_software.problem_id', '=', $id)->get();

            if (!is_null($problem))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Remove Software from Problem.",
                    'desc' => "",
                    'problem' => $problem,
                    'software' => $software,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.select_software_to_remove')->with($data);
            }
            // Redirect to allowed page based on viewer.
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Removes database entry(s) for affected_software
     * Follows from ProblemController@show->select_software_to_remove.
     */
    public function delete_software(Request $request)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {          
            // If all required data is supplied.   
            $this->validate($request, [
                'problem-id' => 'required',
                'software' => 'required',
            ]);

            // Using information supplied as iterable, delete affected_software entries.
            $softwares = $request->input('software');
            $problem_id = $request->input('problem-id');

            foreach ($softwares as $s)
            {
                $a_s = AffectedSoftware::find($s);
                $a_s->delete();
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Software Removed');

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
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Set links based on viewer access.
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            // Get problem object from ID.
            $problem = Problem::find($id);
            if (!is_null($problem))
            {
                // Get information about problem that can be edited.
                $type = ProblemType::find($problem->problem_type);
                $parent = ProblemType::find($type->parent);

                $assigned = DB::table('problems')->join('users', 'problems.assigned_to', '=', 'users.id')->select('users.*')->where('problems.id', '=', $id)->select('users.*')->first();

                $resolved = DB::table('problems')->join('resolved_problems', 'problems.id', '=', 'resolved_problems.problem_id')->select('resolved_problems.solution_notes', 'resolved_problems.created_at')->where('problems.id', '=', $id)->get()->first();

                $importance = Importance::orderBy('level')->get();
                // Supply data to view.
                $data = array(
                    'title' => "Edit Existing Problem",
                    'desc' => "For editing a problem.",
                    'problem'=>$problem,
                    'problem_type' => $type,
                    'parent' => $parent,
                    'specialist'=>$assigned,
                    'resolved'=>$resolved,
                    'importance'=>$importance,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.edit')->with($data);
            }
            // Redirect to allowed page based on viewer.
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function edit_problem_type ($id)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Set links based on viewer access.
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            // Get problem object from ID.
            $problem = Problem::find($id);
            if (!is_null($problem))
            {
                // Get list of problem types.
                $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('problem_types.*, IFNULL(parents.description,0) as parent_description')->get();
                // Supply data to view.
                $data = array(
                    'title' => "Edit Problem Type",
                    'desc' => " ",
                    'problem'=>$problem,
                    'problem_types'=>$problem_types,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.edit_problem_type')->with($data);
            }
            // Redirect to allowed page based on viewer access.
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function add_problem_type ($problem_id, $type_id)
    {
        // If user has operator/specialist access.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Get problem & problem type object from ID.
            $problem = Problem::find($problem_id);
            $pt = ProblemType::find($type_id);
            if (!is_null($problem) && !is_null($pt))

            {
                $problem->problem_type = $pt->id;
                $problem->save();

                return redirect('/problems/'.$problem_id.'/edit')->with('success', 'Problem Type Changed');
            }
            return redirect('/problems/'.$problem_id)->with('error', 'Sorry, something went wrong.');
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function edit_specialist ($id)
    {
        $problem = Problem::find($id);
        if (!is_null($problem))
        {
            if (PagesController::hasAccess(1))
            {
                $assigned = User::find($problem->assigned_to);
                $problem_type = ProblemType::find($problem->problem_type);
                $parent = ProblemType::find($problem_type->parent);

                $specialists = DB::select(DB::raw(
                    "SELECT speciality.id as sID, problem_types.id as pID, problem_types.description, IFNULL(parents.description,0) as parent_description, problem_types.parent, IFNULL(COUNT(problems.id) - COUNT(resolved_problems.id), 0) as jobs, timeoff.startDate, users.*,
                        GROUP_CONCAT(skill_types.description) as skills_list
                    FROM users
                    JOIN speciality
                    ON users.id = speciality.specialist_id
                    JOIN problem_types
                    ON speciality.problem_type_id = problem_types.id
                    LEFT JOIN problem_types as parents
                    ON problem_types.parent = parents.id
                    LEFT JOIN problems
                    ON problems.assigned_to = users.id
                    LEFT JOIN resolved_problems
                    ON resolved_problems.problem_id = problems.id
                    LEFT JOIN timeoff
                    ON (timeoff.user_id = users.id AND 
                        timeoff.id = (
                            SELECT timeoff.id FROM timeoff
                            ORDER BY timeoff.created_at desc
                            LIMIT 1)
                        )
                    LEFT JOIN skills
                    ON users.id = skills.specialist_id
                    LEFT JOIN problem_types as skill_types
                    ON skills.problem_type_id = skill_types.id
                    WHERE
                        (DATE(NOW()) NOT BETWEEN timeoff.startDate AND timeoff.endDate)
                        OR timeoff.id IS NULL
                    GROUP BY users.id, speciality.id, timeoff.startDate
                        "
                ));

                if (is_null($parent))
                {
                    $parent = $problem_type;
                }

                $data = array(
                    'title' => "Edit Assigned Specialist",
                    'desc' => "",
                    'problem'=>$problem,
                    'assigned'=>$assigned,
                    'parent'=>$parent,
                    'type'=>$problem_type,
                    'specialists'=>$specialists,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('problems.edit_specialist')->with($data);
            }
            if (PagesController::hasAccess(2))
            {
                    $data = array(
                        'title' => "Request Problem be Re-assigned?",
                        'desc' => "Doing so will remove the problem from your list of problems.",
                        'problem'=>$problem,
                        'links' => PagesController::getSpecialistLinks(),
                        'active' => 'Problems'
                    );

                    return view('problems.request_reassign')->with($data);
            }
            return redirect('login')->with('error', 'Please log in first.'); 
        }
        return redirect('/problems/'.$id)->with('error', 'Sorry, something went wrong.');
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function solve_compact ($id)
    {
        $problem = Problem::find($id);
        if (!is_null($problem))
        {
            if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
            {
                $data = array(
                    'problem'=>$problem
                );

                return view('problems.solve_compact')->with($data);
            }
            return redirect('login')->with('error', 'Please log in first.'); 
        }
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function solve_problem (Request $request, $id)
    {
        $problem = Problem::find($id);
        if (!is_null($problem))
        {
            $this->validate($request, [
                'notes' => 'required'
            ]);

            if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
            {
                $solver = PagesController::getCurrentUser();

                $resolved = new ResolvedProblem();
                $resolved->problem_id = $problem->id;
                $resolved->solved_by = $solver->id;
                $resolved->solution_notes = $request->input('notes');
                $resolved->save();

                return redirect('/problems/'.$problem->id)->with('success', 'Problem marked as solved.');
            }
            return redirect('/login')->with('error', 'Please log in first.');
        }

        return redirect('/problems/'.$id)->with('error', 'Sorry, something went wrong.');
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function add_specialist($id, $specialist_id)
    {
        $problem = Problem::find($id);
        $user = User::find($specialist_id);
        if (!is_null($problem) && !is_null($user)) {
            if (PagesController::hasAccess(1))
            {
                $problem->assigned_to = $user->id;
                // If this was a reassigning, chain new specialist onto row.
                $r = Reassignments::where('problem_id', '=', $problem->id)->where('reassigned_to', '=', '0')->orderBy('created_at', 'desc')->first();
                if (!is_null($r))
                {
                    $r->reassigned_to = $user->id;
                    $r->save();
                }

                $problem->save();
    
                return redirect('/problems/'.$id.'/edit')->with('success', 'Problem Assigned to '.$user->forename.' '.$user->surname.'.');
            }
    
            return redirect('/login')->with('error', 'Please log in first.');
        }
        return redirect('/problems/'.$id)->with('error', 'Sorry, something went wrong.');
    }
    /** 
     * Selects which equipment to append to problem.
     * Follows from Problem@show->append equipment.
     */
    public function add_operator($id)
    {
        $problem = Problem::find($id);
        if (!is_null($problem))
        {
            if (PagesController::hasAccess(1))
            {
                $user = PagesController::getCurrentUser();
                $problem->assigned_to = $user->id;
                // If this was a reassigning, chain new specialist onto row.
                $r = Reassignments::where('problem_id', '=', $problem->id)->where('reassigned_to', '=', '0')->orderBy('created_at', 'desc')->first();
                if (!is_null($r))
                {
                    $r->reassigned_to = $user->id;
                    $r->save();
                }

                $problem->save();

                return redirect('/problems/'.$id.'/edit')->with('success', 'Problem Assigned to You');
            }

            return redirect('/login')->with('error', 'Please log in first.');
        }
        return redirect('/problems/'.$id)->with('error', 'Sorry, something went wrong.');
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
        $problem = Problem::find($id);
        if (!is_null($problem))
        {
            if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
            {            
                $this->validate($request, [
                    'solved' => 'required',
                ]);
                $desc = $request->input('desc') ?? $problem->description;
                $notes = $request->input('notes') ?? $problem->notes;
                $importance = $request->input('importance') ?? $problem->importance;
                // Find current problem, and change the fields.
                $problem->description = $desc;
                $problem->notes = $notes;
                $problem->importance = $importance;
                $problem->save();

                
                if ($request->input('solved') == 'false')
                {
                    $resolved = ResolvedProblem::where('problem_id', '=', $id);
                    $resolved->delete();
                }
                else if ($request->input('solved') == 'true')
                {
                    $user = PagesController::getCurrentUser();
                    $this->validate($request, [
                        'solution_notes'=>'required'
                    ]);
                    $resolved = ResolvedProblem::where('problem_id', '=', $id)->get()->first();

                    if (!is_null($user))
                    {
                        if (is_null($resolved))
                        {
                            $resolved = new ResolvedProblem();
                            $resolved->problem_id = $id;
                        }
                        $resolved->solution_notes=$request->input('solution_notes');
                        $resolved->solved_by=$user->id;
                        $resolved->save();
                    }
                }

                return redirect('/problems/'.$id)->with('success', 'Problem Updated');
            }
            return redirect('login')->with('error', 'Please log in first.');
        }
        return redirect('/problems/'.$id)->with('error', 'Sorry, something went wrong.');
    }

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
            $problem = Problem::find($id);
            $problem->delete();

            $affected_hardware = AffectedHardware::where('problem_id', '=', $id);
            $affected_hardware->delete();

            $affected_software = AffectedSoftware::where('problem_id', '=', $id);
            $affected_software->delete();

            $calls = Call::where('problem_id', '=', $id);
            $calls->delete();

            $resolved = ResolvedProblem::where('problem_id', '=', $id);
            $resolved->delete();

            return redirect('/problems/')->with('success', 'Problem Permantly Deleted');
        }
    }
}
