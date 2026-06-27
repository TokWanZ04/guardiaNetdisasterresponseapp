<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Fetch all messages for a specific incident.
     */
    public function fetchMessages(Incident $incident)
    {
        // Security check: Only the citizen who triggered the SOS, active Responders, or Admins can access
        $user = auth()->user();
        if ($user->id !== $incident->user_id && $user->role_type !== 'Responder' && $user->role_type !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $incident->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name,
                    'sender_role' => $msg->sender->role_type,
                    'time' => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Send a message in a specific incident chat.
     */
    public function sendMessage(Request $request, Incident $incident)
    {
        $user = auth()->user();
        if ($user->id !== $incident->user_id && $user->role_type !== 'Responder' && $user->role_type !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'incident_id' => $incident->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->name,
                'sender_role' => $user->role_type,
                'time' => $message->created_at->format('H:i'),
            ]
        ]);
    }
}
