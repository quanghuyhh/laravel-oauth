<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('users.home');
    }
}
