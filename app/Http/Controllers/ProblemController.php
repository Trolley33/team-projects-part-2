<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Problem;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'title' => "Problem Viewer",
            'desc' => "Displays all problems.",
            'info' => Problem::all(),
            'links' => $this->operator_links,
            'active' => 'Problems'
        );

        return view('pages.problems.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        return view('pages.problems.create')->with($data);
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
        $problem = Problem::find($id);

        $callers = DB::table('problems')->join('calls', 'problems.id', '=', 'calls.problem_id')->join('users', 'users.id', '=', 'calls.caller_id')->select('calls.*', 'users.*')->where('problems.id', '=', $id)->get();

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
            return view('pages.problems.show')->with($data);
        }
        return "Error completing that request.";
    }

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
