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

        // Prevent spam: Check if user already has an active unresolved incident
        if (auth()->user()->incidents()->where('status', '!=', 'Resolved')->exists()) {
            return back()->with('error', 'You already have an active SOS dispatch! Please wait for responders to resolve it.');
        }

        Incident::create([
            'user_id' => auth()->id(),
            'location' => $request->location,
            'type' => $request->type,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'SOS Alert sent successfully. Responders will be dispatched shortly.');
    }

    public function getActiveIncidentsHTML()
    {
        $activeIncidents = auth()->user()->incidents()
            ->with(['responseLogs.responder'])
            ->where('status', '!=', 'Resolved')
            ->latest()
            ->get();
            
        return view('citizen.partials.active-incidents', compact('activeIncidents'));
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

    /**
     * Fetch active weather warnings dynamically from the official MET Malaysia API
     * (api.data.gov.my) to render on the citizen's dashboard.
     */
    public function getWeatherAlerts()
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get('https://api.data.gov.my/weather/warning');
            if ($response->successful()) {
                $warnings = $response->json();

                // Filter out empty advisories (e.g. "No Advisory")
                $activeWarnings = array_filter($warnings, function($w) {
                    return isset($w['heading_en']) && $w['heading_en'] !== 'No Advisory';
                });

                return response()->json([
                    'success' => true,
                    'warnings' => array_values(array_map(function($w) {
                        return [
                            'title' => $w['title_en'] ?? $w['heading_en'] ?? 'Weather Alert',
                            'title_bm' => $w['title_bm'] ?? $w['heading_bm'] ?? 'Amaran Cuaca',
                            'text' => $w['text_en'] ?? '',
                            'text_bm' => $w['text_bm'] ?? '',
                            'valid_to' => isset($w['valid_to']) ? date('d M Y, h:i A', strtotime($w['valid_to'])) : 'N/A',
                            'issued' => isset($w['warning_issue']['issued']) ? date('d M Y, h:i A', strtotime($w['warning_issue']['issued'])) : 'N/A'
                        ];
                    }, array_slice($activeWarnings, 0, 3)))
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Weather API Error: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'warnings' => []]);
    }
}
