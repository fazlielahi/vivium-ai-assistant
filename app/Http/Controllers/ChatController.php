<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Conversation;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = trim($request->message);

        $sessionId = session()->getId();

        // Save user message
        Conversation::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'message' => $message,
        ]);

        // Load conversation history
        $history = Conversation::where('session_id', $sessionId)
            ->latest()
            ->take(config('ai.history_limit'))
            ->get()
            ->reverse()
            ->values();

        $ai = new AIService();

        try {

            $aiResponse = $ai->chat($history);

            $json = json_decode($aiResponse, true);

            if (
                json_last_error() !== JSON_ERROR_NONE ||
                !isset($json['message']) ||
                !isset($json['booking_completed'])
            ) {

                throw new \Exception('Invalid AI JSON response.');

            }

            $reply = $json['message'];

            if ($json['booking_completed'] === true) {

                Appointment::firstOrCreate(

                    [
                        'phone' => $json['appointment']['phone'],
                        'appointment_date' => $json['appointment']['appointment_date'],
                    ],

                    [
                        'name' => $json['appointment']['name'],
                        'service' => $json['appointment']['service'],
                    ]

                );

            }

            Conversation::create([
                'session_id' => $sessionId,
                'role' => 'assistant',
                'message' => $reply,
            ]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            if (str_contains($e->getMessage(), '429')) {

                $reply = 'Our AI assistant is currently busy due to high demand. Please try again in a minute.';

            } else {

                $reply = 'Sorry, our assistant is temporarily unavailable. Please try again later.';

            }

            Conversation::create([
                'session_id' => $sessionId,
                'role' => 'assistant',
                'message' => $reply,
            ]);

        }

        return response()->json([
            'reply' => $reply,
        ]);
    }
}