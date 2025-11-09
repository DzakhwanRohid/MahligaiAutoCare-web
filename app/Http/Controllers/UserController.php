<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role',['user','kasir'])
                    -> orderBy('name','asc')
                    -> paginate(10);
        return view('admin.users.index',compact('users'));
    }
}
