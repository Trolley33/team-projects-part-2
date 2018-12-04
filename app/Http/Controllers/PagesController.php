<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;

class PagesController extends Controller
{

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

        if (!is_null($result))
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

    public function index()
    {

        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Operator Homepage",
                'desc' => "Please select a task."
            );
            return redirect('/operator')->with($data);
        }
        if ($this->hasAccess(2))
        {
            $data = array(
                'title' => "Specialist Homepage",
                'desc' => "Please select a task."
            );
            return redirect('/specialist')->with($data);
        }
        if ($this->hasAccess(3))
        {
            $data = array(
                'title' => "Analyst Homepage",
                'desc' => "Please select a task."
            );
            return redirect('/analyst')->with($data);
        }

        $data = array(
            'title' => "Make-It-All Helpdesk",
            'desc' => "For submitting and receiving tehnical queries.",
            'noback'=>'true'
        );
        return view('pages.index')->with($data);
    }

    public function login()
    {
        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Operator Homepage",
                'desc' => "Please select a task."
            );
            return redirect('/operator')->with($data);
        }
        if ($this->hasAccess(2))
        {
            $data = array(
                'title' => "Specialist Homepage",
                'desc' => "Please select a task."
            );
            return redirect('/specialist')->with($data);
        }
        if ($this->hasAccess(3))
        {
            $data = array(
                'title' => "Analyst Homepage",
                'desc' => "Please select a task."
            );
            return redirect('/analyst')->with($data);
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
        if (!is_null($result))
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
        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Operator Homepage",
                'desc' => "Please select a task."
            );
            return view('pages.operator.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function caller_info()
    {
        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Caller Details",
                'desc' => "Search for Caller Details"
            );
            return view('pages.operator.caller_info')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function caller_info_from_name($name)
    {
        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Caller Details",
                'desc' => "",
                'name' => $name
            );
            return view('pages.operator.display_caller_info')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');

    }

    public function log_call()
    {
        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Log New Call",
                'desc' => "",
                'id' => ''
            );

            return view('pages.operator.log_call')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    // ==== Specialist pages. ====
    public function specialist_homepage()
    {
        if ($this->hasAccess(2))
        {
            $data = array(
                'title' => "Specialist Homepage",
                'desc' => "Please select a task."
            );
            return view('pages.specialist.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }


    // ==== Analyst pages. ====
    public function analyst_homepage()
    {
        if ($this->hasAccess(3))
        {
            $data = array(
                'title' => "Analyst Homepage",
                'desc' => "Please select a task."
            );
            return view('pages.analyst.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }



    // ==== Problem pages. ====

    public function view_problems()
    {
        if ($this->hasAccess(1) || $this->hasAccess(2) || $this->hasAccess(3))
        {
            $data = array(
                'title' => "Problem Viewer",
                'desc' => "Shows all ongoing and resolved problems."
            );

            return view('pages.problems.view_all')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');      
    }

    // Single problem
    public function view_problem($id)
    {
        if ($this->hasAccess(1) || $this->hasAccess(2) || $this->hasAccess(3))
        {
            $data = array(
                'title' => "Problem Viewer",
                'desc' => "Shows all ongoing and resolved problems.",
                'id' => $id
            );

            return view('pages.problems.view_problem')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
