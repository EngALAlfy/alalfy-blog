<?php

return [
    // Gemini API key (put in your .env as GEMINI_API_KEY=your_key)
    'gemini_api_key' => env('GEMINI_API_KEY'),

    // Default model name
    'gemini_model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),

    // HTTP timeout (seconds)
    'timeout' => env('AI_HTTP_TIMEOUT', 15),
];

