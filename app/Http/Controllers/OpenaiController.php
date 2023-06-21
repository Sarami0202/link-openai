<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpenaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function CreateQuestion(Request $request)
    {

        $apikey = "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";
        $url = "https://api.openai.com/v1/chat/completions";

        // リクエストヘッダー
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('services.openai.api')
        );

        // リクエストボディ
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ["role" => "system", "content" => "あなたは日本語教師です"],
                ["role" => "system", "content" => "問題と選択肢のみ出力"],
                ["role" => "system", "content" => "正解は出力しない"],
                ["role" => "system", "content" => "日本語能力試験（JLPT）" . $request->level . "レベル"],
                ["role" => "system", "content" => "出力は問題文から始めること"],
                ["role" => "system", "content" => "問題のテーマは「" . $request->theme . "」です"],
                ['role' => 'user', 'content' => "日本語能力試験（JLPT）の問題を出力してください。"],
            ],
            'max_tokens' => 500,
        ];
        // cURLを使用してAPIにリクエストを送信 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  config('services.openai.url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // 結果をデコード
        $result = json_decode($response, true);
        $result_message = $result["choices"][0]["message"]["content"];

        // 結果を出力  
        return $this->JsonResponse($result_message);

    }
    public function CreateHint(Request $request)
    {

        // リクエストヘッダー
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('services.openai.api')
        );

        // リクエストボディ
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ["role" => "system", "content" => "あなたは日本語教師です"],
                ["role" => "system", "content" => "外国人にも分かりやすく"],
                ["role" => "system", "content" => "問題が複数の場合はヒントもそれぞれ出力"],
                ['role' => 'user', 'content' => "日本語能力試験（JLPT）の問題を出力してください。"],
                ["role" => "assistant", "content" => $request->result],
                ['role' => 'user', 'content' => "ヒントを教えてください。".$request->language."で教えてください"],
            ],
            'max_tokens' => 500,
        ];
        // cURLを使用してAPIにリクエストを送信 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('services.openai.url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // 結果をデコード
        $result = json_decode($response, true);
        $result_message = $result["choices"][0]["message"]["content"];

        // 結果を出力  
        return $this->JsonResponse($result_message);

    }
    public function CreateAnswer(Request $request)
    {

        // リクエストヘッダー
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('services.openai.api')
        );

        // リクエストボディ
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => "日本語能力試験（JLPT）の問題を出力してください。"],
                ["role" => "assistant", "content" => $request->result],
                ['role' => 'user', 'content' => "答えを教えてください"],
            ],
            'max_tokens' => 500,
        ];
        // cURLを使用してAPIにリクエストを送信 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('services.openai.url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // 結果をデコード
        $result = json_decode($response, true);
        $result_message = $result["choices"][0]["message"]["content"];

        // 結果を出力  
        return $this->JsonResponse($result_message);

    }
    public function CreateTranslation(Request $request)
    {

        $apikey = "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";
        $url = "https://api.openai.com/v1/chat/completions";

        // リクエストヘッダー
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('services.openai.api')
        );

        // リクエストボディ
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ["role" => "system", "content" => "あなたは翻訳者です"],
                ["role" => "system", "content" => $request->language . "を日本語に翻訳してください"],
                ["role" => "system", "content" => "自然に話すように翻訳してください"],
                ["role" => "system", "content" => "翻訳した結果のみ出力してください"],
                ["role" => "system", "content" => "日本語で出力"],
                ["role" => "system", "content" => "語尾に「だよ。」「んだ。」「です。」「ます。」をそれぞれ3回以上連続で使用しない"],
                ["role" => "system", "content" => "語尾に「う。」「ね。」を連続で使用しない"],
                ['role' => 'user', 'content' => $request->text],
            ],
            'max_tokens' => 500,
        ];

        // cURLを使用してAPIにリクエストを送信 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  config('services.openai.url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // 結果をデコード
        $result = json_decode($response, true);
        $result_message = $result["choices"][0]["message"]["content"];

        // 結果を出力  
        return $this->JsonResponse($result_message);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}