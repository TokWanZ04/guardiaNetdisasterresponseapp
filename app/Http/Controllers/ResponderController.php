<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Incident;
use App\Models\ResponseLog;

class ResponderController extends Controller
{
    public function index()
    {
        $incidents = Incident::with('user')->whereIn('status', ['Pending', 'En Route', 'On Scene'])->latest()->get();
        return view('responder.dashboard', compact('incidents'));
    }

    public function updateStatus(Request $request, Incident $incident)
    {
        $request->validate([
            'status' => 'required|in:En Route,On Scene,Resolved',
        ]);

        $incident->update(['status' => $request->status]);

        ResponseLog::create([
            'incident_id' => $incident->id,
            'responder_id' => auth()->id(),
            'action_taken' => 'Status updated to ' . $request->status,
        ]);

        return back()->with('success', 'Incident status updated successfully.');
    }

    public function updateResponderLocation(Request $request, Incident $incident)
    {
        $request->validate([
            'location' => 'required|string',
        ]);

        $incident->update([
            'responder_location' => $request->location
        ]);

        return response()->json(['success' => true]);
    }

    public function getResponderLocation(Incident $incident)
    {
        return response()->json([
            'status' => $incident->status,
            'responder_location' => $incident->responder_location,
            'incident_location' => $incident->location
        ]);
    }
}
