<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;

class PagesController extends Controller
{

    public static function getOperatorLinks ()
    {
        return [
            ['href'=>'back','text'=>'back'],
            ['href'=>'operator','text'=>'Home'],
            ['href'=>'problems','text'=>'Problems'],
            ['href'=>'users','text'=>'Users'],
            ['href'=>'departments','text'=>'Departments'],
            ['href'=>'jobs','text'=>'Jobs'],
            ['href'=>'equipment','text'=>'Equipment'],
            ['href'=>'software','text'=>'Software'],
            ['href'=>'problem_types','text'=>'Problem Types']
        ];
    }

    public static function getSpecialistLinks ()
    {
        return [
            ['href'=>'back','text'=>'back'],
            ['href'=>'specialist','text'=>'Home']
        ];
    }

    public static function hasAccess($level)
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

        if (!is_null($result) && count($result) == 1)
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

    public static function getCurrentUser()
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2) || PagesController::hasAccess(3))
        {
            $cookie = $_COOKIE['csrf'];
            $user = User::where('remember_token', '=', $cookie)->get()->first();

            return $user;
        }

        return null;
    }

    public function index()
    {

        if (PagesController::hasAccess(1))
        {
            return redirect('/operator');
        }
        if (PagesController::hasAccess(2))
        {
            return redirect('/specialist');
        }
        if (PagesController::hasAccess(3))
        {
            return redirect('/analyst');
        }

        $data = array(
            'title' => "Make-It-All Helpdesk",
            'desc' => "For submitting and receiving tehnical queries."
        );
        return view('pages.index')->with($data);
    }

    public function login()
    {
        if (PagesController::hasAccess(1))
        {
            return redirect('/operator');
        }
        if (PagesController::hasAccess(2))
        {
            return redirect('/specialist');
        }
        if (PagesController::hasAccess(3))
        {
            return redirect('/analyst');
        }
        
        $data = array(
            'title' => "Login Page",
            'desc' => "Please log in with your user credentials below."
        );

        return view('pages.login')->with($data);
    }

    public function logout()
    {
        setcookie("csrf", "", time()-3600);

        $data = array(
            'title' => "Login Page",
            'desc' => "Please log in with your user credentials below."
        );

        return redirect('/')->with('success', 'Logged Out', $data);
    }

    public function FAQ()
    {
        $data = array(
            'title' => "FAQ",
            'desc' => ""
        );

        return view('pages.faq')->with($data);
    }

    public function verify()
    {
        // do database query
        $name = $_POST['username'];
        $pass = $_POST['password'];
        $token = $_POST['tok'];

        $result = DB::table('users')->select('users.id')->where('users.username', '=', $name, 'AND', 'users.password', '=', $pass)->get();
        if (!is_null($result) && count($result) == 1)
        {
            $id = $result->first()->id;
            $user = User::find($id);
            $job = Job::find($user->job_id);

            setcookie('csrf', $token, time() + 86400, "/");

            $user->remember_token = $token;
            $user->save();

            $level = $job->access_level;
            if ($level == 1)
            {
                return redirect('operator/');
            }
            if ($level == 2)
            {
                return redirect('specialist/');
            }
            if ($level == 3)
            {
                return redirect('analyst/');
            }
        }
        return redirect('login');

    }

    // ==== Operator pages. ====
    public function operator_homepage()
    {
        if (PagesController::hasAccess(1))
        {
            $data = array(
                'title' => "Operator Homepage",
                'desc' => "Please select a task.",
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Home'
            );
            return view('pages.operator.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }


    // ==== Specialist pages. ====
    public function specialist_homepage()
    {
        if (PagesController::hasAccess(2))
        {
            $specialist = PagesController::getCurrentUser();

            $problems = DB::select(DB::raw(
                "SELECT problems.id as id, problems.created_at, problem_types.description as ptDesc, problems.description, problems.assigned_to, problems.importance, IFNULL(parents.description,0) as pDesc, users.forename, users.surname, calls.id as cID
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
                WHERE problems.assigned_to = ".$specialist->id.";"
            ));

            $data = array(
                'title' => "Specialist Homepage",
                'desc' => "Please select a task.",
                'user' => $specialist,
                'problems' => $problems,
                'links' => PagesController::getSpecialistLinks(),
                'active' => 'Home'
            );
            return view('pages.specialist.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }


    // ==== Analyst pages. ====
    public function analyst_homepage()
    {
        if (PagesController::hasAccess(3))
        {
            $data = array(
                'title' => "Analyst Homepage",
                'desc' => "Please select a task."
            );
            return view('pages.analyst.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}