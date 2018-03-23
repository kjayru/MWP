<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function getTokens()
    {
        return view('personal-tokens');
    }

    public function getAuthorizedClients()
    {
        return view('authorized-clients');
    }

    public function getClients()
    {
        return view('personal-clients');
    }

}
