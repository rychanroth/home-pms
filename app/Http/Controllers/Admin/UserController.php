<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::enum(UserRole::class)],
        ]);

        // No need to manually bcrypt the password! 
        // The model's 'hashed' cast takes care of it automatically.
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created!');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => ['required', Rule::enum(UserRole::class)],
        ]);

        // Remove password from the array if it was left blank in the form
        if (empty($validated['password'])) {
            unset($validated['password']); 
        }

        // Again, no manual bcrypt needed if they provided a new password.
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated!');
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