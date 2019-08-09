<?php

namespace Craftworks\TaskManager\Http\Controllers;

use Craftworks\TaskManager\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', [
            'users' => $users,
            'initIteration' => ($users->currentPage() - 1) * $users->perPage()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed']
        ]);

        auth()->user()->name = $data['name'];
        
        if ($data['password']) {
            auth()->user()->password = Hash::make($data['password']);
        }

        auth()->user()->save();

        return redirect()->route('home')->with('success', __('Your account has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user = auth()->user();
        
        auth()->guard()->logout();
        $request->session()->invalidate();
        $user->delete();
        return redirect()->route('home');
    }
}
