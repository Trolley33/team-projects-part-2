<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;
use App\Software;
use App\Speciality;
use App\ProblemType;

class SpecialityController extends Controller
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
            $parents = ProblemType::where('parent', '=', '-1')->get();

            $data = array(
                'title' => "Speciality Viewer",
                'desc' => "View information on specialist's specialities.",
                'parents' => $parents,
                'links'=>$this->operator_links,
                'active'=>'Specialities'
            );

            return view('specialities.index')->with($data);
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
        //
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
            $problem_type = ProblemType::find($id);
            if (!is_null($problem_type))
            {
                $specialists = Speciality::join('users', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('problem_types.id', '=', $id)->orWhere('problem_types.parent', '=', $id)->get();

                if ($problem_type->parent == '-1')
                {
                    $types = ProblemType::where('parent', '=', $id)->get();

                    $data = array(
                        'title' => "Problem Type Viewer",
                        'desc' => "View information on parent.",
                        'parent'=>$problem_type,
                        'types' => $types,
                        'specialists'=>$specialists,
                        'links'=>$this->operator_links,
                        'active'=>'Specialities'
                    );

                    return view('specialities.show_parent')->with($data);
                }
                else
                {
                    $parent = ProblemType::find($problem_type->parent);

                    $data = array(
                        'title' => "Problem Type Viewer.",
                        'desc' => "View information on problem types.",
                        'problem_type' => $problem_type,
                        'parent'=>$parent,
                        'specialists'=>$specialists,
                        'links' => $this->operator_links,
                        'active' => 'Specialities'
                    );

                    return view('specialities.show_child')->with($data);
                }
            }
            return "Something else";
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
