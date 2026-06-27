<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Incident;
use App\Models\Alert;

class AdminController extends Controller
{
    public function index()
    {
        $incidents = Incident::with('user', 'responseLogs.responder')->latest()->get();
        $alerts = Alert::with('admin')->latest()->get();
        $responders = \App\Models\User::where('role_type', 'Responder')->latest()->get();
        $tab = request('tab', 'home');
        return view('admin.dashboard', compact('incidents', 'alerts', 'responders', 'tab'));
    }

    public function storeAlert(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Alert::create([
            'admin_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Emergency alert pushed successfully.');
    }

    public function destroyAlert(Alert $alert)
    {
        $alert->delete();
        return back()->with('success', 'Emergency broadcast deleted successfully.');
    }

    public function storeResponder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'phone' => $request->phone,
            'role_type' => 'Responder',
        ]);

        return back()->with('success', 'First responder registered successfully.');
    }

    public function updateResponder(Request $request, \App\Models\User $user)
    {
        // Security check: ensure target is a responder
        if ($user->role_type !== 'Responder') {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'First responder updated successfully.');
    }

    public function destroyResponder(\App\Models\User $user)
    {
        // Security check: ensure target is a responder
        if ($user->role_type !== 'Responder') {
            return back()->with('error', 'Unauthorized action.');
        }

        $user->delete();
        return back()->with('success', 'First responder deleted successfully.');
    }
}
