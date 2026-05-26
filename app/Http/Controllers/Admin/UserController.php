<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        // THE PROTECTION WALL: Cannot delete ID 1
        if ($user->id === 1) {
            return back()->withErrors(['error' => 'Cannot delete the primary administrator account.']);
        }

        // Prevent suicide (can't delete yourself)
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted!');
    }
}