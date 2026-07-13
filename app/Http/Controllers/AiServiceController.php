<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiServiceController extends Controller
{
    /**
     * Analyze user's problem description and suggest a service category.
     */
    public function suggest(Request $request)
    {
        $request->validate([
            'problem' => 'required|string|min:2|max:1000',
        ]);

        $problem = $request->problem;
        $skills = Skill::pluck('name')->toArray();
        $apiKey = env('GROQ_API_KEY');

        if (!empty($apiKey)) {
            try {
                $systemPrompt = "You are a friendly chatbot assistant for ServiceHub BD. "
                    . "If the user is describing a household issue or needing repair help, select the single most relevant service from the following list: " . implode(', ', $skills) . ".\n"
                    . "If they are just chatting, greeting you, or asking general questions, set \"suggested_service\" to null.\n\n"
                    . "Provide a friendly, conversational chat response in the \"explanation\" field. Keep it concise (max 3 sentences) and speak in the language (English or Bengali) they used.\n\n"
                    . "You must output only a JSON response in the following format:\n"
                    . "{\n"
                    . "  \"suggested_service\": \"Name of the service from the list, or null\",\n"
                    . "  \"explanation\": \"Your friendly response, chat reply, or explanation\"\n"
                    . "}";

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post("https://api.groq.com/openai/v1/chat/completions", [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $problem
                        ]
                    ],
                    'response_format' => [
                        'type' => 'json_object'
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['choices'][0]['message']['content'] ?? '';
                    $jsonDecoded = json_decode($text, true);

                    if (is_array($jsonDecoded) && (array_key_exists('suggested_service', $jsonDecoded) || array_key_exists('explanation', $jsonDecoded))) {
                        $suggested = $jsonDecoded['suggested_service'] ?? null;
                        $explanation = $jsonDecoded['explanation'] ?? '';

                        if ($suggested && strtolower($suggested) !== 'null') {
                            return response()->json([
                                'success' => true,
                                'suggested_service' => $suggested,
                                'explanation' => $explanation,
                                'source' => 'Groq AI'
                            ]);
                        } else {
                            return response()->json([
                                'success' => true,
                                'suggested_service' => null,
                                'explanation' => !empty($explanation) ? $explanation : 'I couldn\'t identify a matching service category for that.'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'success' => true,
                            'suggested_service' => null,
                            'explanation' => !empty($text) ? $text : 'I couldn\'t process that request, please try again.'
                        ]);
                    }
                } else {
                    $errData = $response->json();
                    $errMsg = is_array($errData) ? ($errData['error']['message'] ?? null) : null;
                    return response()->json([
                        'success' => false,
                        'message' => 'Groq API Error (HTTP ' . $response->status() . '): ' . ($errMsg ?? $response->body())
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Groq API Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Groq Exception: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Groq API Key is not configured. Please add GROQ_API_KEY to your .env file.'
        ]);
    }
}
