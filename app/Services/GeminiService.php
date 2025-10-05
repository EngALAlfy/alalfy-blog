<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeminiService
{
    /**
     * Generate post meta (title, short_description, tags) from content using Gemini API.
     * Falls back to a local heuristic if no API key is configured.
     *
     * @param string $content
     * @return array{title?:string,short_description?:string,tags?:array<int,string>}
     */
    public function generateMeta(string $content): array
    {
        $content = trim($content);
        if ($content === '') {
            return [];
        }

        // Reverted to ai.* config keys per request
        $apiKey = config('ai.gemini_api_key');
        $model = config('ai.gemini_model', 'gemini-2.0-flash');
        $timeout = (int) config('ai.timeout', 15);

        if (empty($apiKey)) {
            return $this->heuristicFallback($content);
        }

        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $prompt = <<<PROMPT
    أنت مساعد ذكي متخصص في استخراج بيانات الميتا لمقالات المدونات المكتوبة بالعربية أو الإنجليزية.
    المطلوب منك تحليل محتوى المقال التالي وإنتاج:
    - title: عنوان جذّاب لا يتجاوز 70 حرفًا، بنفس لغة المقال (غالبًا العربية)، مكتوب بطريقة تسويقية وواضحة.
    - short_description: وصف قصير لا يتجاوز 400 حرفًا، باللغة نفسها، بأسلوب تسويقي يجذب القارئ دون علامات تنصيص.
    - tags: من 3 إلى 8 كلمات دلالية (SEO tags) باللغة نفسها، كلها بحروف صغيرة، بدون علامات ترقيم أو تكرار، كل تاج من 1 إلى 3 كلمات فقط.

    يجب أن يكون الإخراج مطابِقًا للبنية المطلوبة تمامًا دون أي شرح إضافي.

    المحتوى:
    <<<CONTENT>>>
    {$content}
    <<<END>>>
PROMPT;


        try {
            $payload = [
                'contents' => [ [ 'parts' => [ ['text' => $prompt] ] ] ],
                'generationConfig' => [
                    'temperature' => 0.55,
                    'topP' => 0.8,
                    'topK' => 40,
                    'candidateCount' => 1,
                    'maxOutputTokens' => 512,
                    'responseMimeType' => 'application/json',
                    'responseSchema' => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'title' => ['type' => 'STRING'],
                            'short_description' => ['type' => 'STRING'],
                            'tags' => [
                                'type' => 'ARRAY',
                                'items' => ['type' => 'STRING']
                            ],
                        ],
                        'required' => ['title', 'short_description', 'tags'],
                    ],
                ],
            ];

            $response = Http::timeout($timeout)
                ->acceptJson()
                ->asJson()
                ->post($apiUrl, $payload);

            if ($response->failed()) {
                return $this->heuristicFallback($content);
            }

            $raw = $response->json('candidates.0.content.parts.0.text');
            if (!is_string($raw) || $raw === '') {
                return $this->heuristicFallback($content);
            }

            $data = json_decode($raw, true);
            if (!is_array($data)) {
                // Attempt to extract JSON if model added extra text
                $json = $this->extractJson($raw);
                $data = $json ? json_decode($json, true) : null;
            }
            if (!is_array($data)) {
                return $this->heuristicFallback($content);
            }

            $result = [];
            if (isset($data['title']) && is_string($data['title'])) {
                $result['title'] = trim($data['title']);
            }
            if (isset($data['short_description']) && is_string($data['short_description'])) {
                $cleanShort = preg_replace('/\s+/', ' ', trim($data['short_description']));
                $result['short_description'] = Str::limit($cleanShort, 500, '');
            }
            if (isset($data['tags']) && is_array($data['tags'])) {
                $tags = collect($data['tags'])
                    ->filter(fn ($t) => is_string($t))
                    ->map(fn ($t) => strtolower(trim($t)))
                    ->filter(fn ($t) => $t !== '')
                    ->unique()
                    ->values()
                    ->all();
                if (count($tags) >= 3) {
                    $result['tags'] = $tags;
                }
            }

            // Validate completeness; else fallback
            if (!isset($result['title'], $result['short_description'], $result['tags'])) {
                return $this->heuristicFallback($content);
            }

            return $result;
        } catch (\Throwable $e) {
            report($e);
            return $this->heuristicFallback($content);
        }
    }

    protected function heuristicFallback(string $content): array
    {
        $plain = strip_tags($content);
        $words = preg_split('/\s+/', $plain) ?: [];
        $title = Str::title(implode(' ', array_slice($words, 0, 8)));
        $short = Str::limit(implode(' ', array_slice($words, 0, 40)), 180, '');

        $stop = collect(['the','and','for','with','that','this','from','your','into','using','about','into','when','have','are','you','will','into']);
        $tags = collect($words)
            ->map(fn ($w) => strtolower(preg_replace('/[^a-z0-9-]/i', '', $w)))
            ->filter(fn ($w) => strlen($w) > 3 && !$stop->contains($w))
            ->take(8)
            ->unique()
            ->values()
            ->all();

        return [
            'title' => $title,
            'short_description' => $short,
            'tags' => $tags,
        ];
    }

    protected function extractJson(string $text): ?string
    {
        if (preg_match('/{(?:[^{}]|(?R))*}/s', $text, $m)) {
            return $m[0];
        }
        return null;
    }
}
