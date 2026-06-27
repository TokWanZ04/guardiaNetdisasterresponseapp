<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alert;
use App\Models\Incident;

class CitizenController extends Controller
{
    public function index()
    {
        $alerts = Alert::latest()->take(5)->get();
        
        $activeIncidents = auth()->user()->incidents()
            ->with(['responseLogs.responder'])
            ->where('status', '!=', 'Resolved')
            ->latest()
            ->get();
        
        $resolvedIncidents = auth()->user()->incidents()
            ->with(['responseLogs.responder'])
            ->where('status', 'Resolved')
            ->latest()
            ->take(5)
            ->get();

        return view('citizen.dashboard', compact('alerts', 'activeIncidents', 'resolvedIncidents'));
    }

    public function storeSOS(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'type' => 'required|in:Medical,Fire,Flood',
        ]);

        Incident::create([
            'user_id' => auth()->id(),
            'location' => $request->location,
            'type' => $request->type,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'SOS Alert sent successfully. Responders will be dispatched shortly.');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'diseases' => 'nullable|string',
        ]);

        $validated['is_pwd'] = $request->has('is_pwd');

        $request->user()->update($validated);

        return back()->with('success', 'Medical profile updated successfully.');
    }
}
