<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
      return $this->middleware('auth');
    }

    public function index()
    {
      return view('users.index')->with('users', User::latest()->get());
    }

    public function addAdmin($id)
    {
      $u = User::find($id);
      $u->admin = true;
      $u->save();
      return $u->admin;
    }

    public function removeAdmin($id)
    {
      $u = User::find($id);
      $u->admin = false;
      $u->save();
      return !$u->admin;
    }

    public function delete($id)
    {
      return User::find($id)->delete();
    }
}
