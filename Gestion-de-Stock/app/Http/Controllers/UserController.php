<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = Utilisateur::all();
        return view('users.index', compact('users'));
    }
}
