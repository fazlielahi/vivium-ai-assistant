<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Main entry point.
     */
    public function chat(Collection $history)
    {
        $messages = $this->buildMessages($history);

        return $this->sendToAI($messages);
    }

    /**
     * Load system prompt.
     */
    private function getSystemPrompt(): string
    {
        return file_get_contents(
            app_path('Prompts/system_prompt.txt')
        );
    }

    /**
     * Build conversation for the AI.
     */
    private function buildMessages(Collection $history): array
    {
        $messages = [];

        $messages[] = [
            'role' => 'system',
            'content' =>
                $this->getSystemPrompt()
                . "\n\nKnowledge:\n"
                . json_encode(config('knowledge'), JSON_PRETTY_PRINT)
        ];

        foreach ($history as $chat) {

            $messages[] = [

                'role' => $chat->role,

                'content' => $chat->message

            ];

        }

        return $messages;
    }

    /*
    |--------------------------------------------------------------------------
    | OpenAI (Future)
    |--------------------------------------------------------------------------
    */

    /*
    private function sendToAI(array $messages)
    {
        $response = Http::withToken(config('openai.api_key'))
            ->post('https://api.openai.com/v1/chat/completions', [

                'model' => 'gpt-4.1-mini',

                'messages' => $messages,

                'temperature' => 0.3,

            ]);

        if (!$response->successful()) {

            Log::error($response->body());

            throw new \Exception($response->body());

        }

        return $response->json()['choices'][0]['message']['content'];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Google Gemini (Future)
    |--------------------------------------------------------------------------
    */

    /*
    private function sendToAI(array $messages)
    {
        $text = "";

        foreach ($messages as $message) {

            $text .= strtoupper($message['role']) . ":\n";
            $text .= $message['content'] . "\n\n";

        }

        $response = Http::acceptJson()->post(

            "https://generativelanguage.googleapis.com/v1beta/models/"
            . config('gemini.model')
            . ":generateContent?key="
            . config('gemini.api_key'),

            [

                "contents" => [

                    [

                        "parts" => [

                            [

                                "text" => $text

                            ]

                        ]

                    ]

                ]

            ]

        );

        if (!$response->successful()) {

            Log::error($response->body());

            throw new \Exception($response->body());

        }

        return $response->json()['candidates'][0]['content']['parts'][0]['text'];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | OpenRouter (Current)
    |--------------------------------------------------------------------------
    */

    private function sendToAI(array $messages)
    {
        $response = Http::withHeaders([

            'Authorization' => 'Bearer ' . config('openrouter.api_key'),

            'Content-Type' => 'application/json',

            'HTTP-Referer' => 'http://localhost',

            'X-Title' => 'Vivium Assessment',

        ])->post('https://openrouter.ai/api/v1/chat/completions', [

            'model' => config('openrouter.model'),

            // IMPORTANT
            'messages' => $messages,

            'temperature' => 0.3,

        ]);

        if (!$response->successful()) {

            Log::error($response->body());

            throw new \Exception($response->body());

        }

        return $response->json()['choices'][0]['message']['content'] ?? '';
    }
}