<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function Translation(Request $request)
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
                ["role" => "system", "content" => "翻訳結果のみ出力"],
                ['role' => 'user', 'content' => "「" . $request->result1 . "」を" . $request->language . "に翻訳してください。"],
            ],
            'max_tokens' => 2000,
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
    public function Conversion(Request $request)
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
                [
                    "role" => "system",
                    "content" => "  問題生成サンプルを以下に列挙します。

                    もんだい：
                    「収集」の読みかたは次のうちどれでしょうか？            
                    a) しゅうしゅう
                    b) しょうしゅう
                    c) しゅうしゅ
                    d) しゅうしょう

                    もんだい：
                    「代表」の読みかたは次のうちどれでしょうか？    
                    a) だいひょ
                    b) だいひょう
                    c) たいひょ
                    d) たいひょう

                    もんだい：
                    「友人」の読みかたは次のうちどれでしょうか？
                    a) ともだち
                    b) ゆうしん
                    c) ゆうじん
                    d) ゆうひと

                上記例を参考に、難易度や問題の作り方を模倣し、問題のみ生成してください。
                ではシミュレーションを開始します。"
                ],
                [
                    "role" => "system",
                    "content" => "日本語能力試験の「漢字読み」問題を生成します。
                答えを聞かれた場合は答えを問題ではなく生成してください"
                ],
                ["role" => "system", "content" => "userの言語ではなく日本語で話して"],
                ["role" => "system", "content" => "積極的に会話を展開してください。"],
                ['role' => 'user', 'content' => $request->message1],
            ],
            'max_tokens' => 2000,
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

    public function onConversion(Request $request)
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
                [
                    "role" => "system",
                    "content" => "  問題生成サンプルを以下に列挙します。

                    もんだい：
                    「収集」の読みかたは次のうちどれでしょうか？            
                    a) しゅうしゅう
                    b) しょうしゅう
                    c) しゅうしゅ
                    d) しゅうしょう

                    もんだい：
                    「代表」の読みかたは次のうちどれでしょうか？    
                    a) だいひょ
                    b) だいひょう
                    c) たいひょ
                    d) たいひょう

                    もんだい：
                    「友人」の読みかたは次のうちどれでしょうか？
                    a) ともだち
                    b) ゆうしん
                    c) ゆうじん
                    d) ゆうひと

                上記例を参考に、難易度や問題の作り方を模倣し、問題のみ生成してください。
                ではシミュレーションを開始します。"
                ],
                [
                    "role" => "system",
                    "content" => "日本語能力試験の「漢字読み」問題を生成します。
                答えを聞かれた場合は答えを問題ではなく生成してください"
                ],
                ["role" => "system", "content" => "userの言語ではなく日本語で話して"],
                ["role" => "system", "content" => "積極的に会話を展開してください。"],
                ['role' => 'user', 'content' => $request->message1],
                ["role" => "assistant", "content" => $request->result1],
                ['role' => 'user', 'content' => $request->message2],
            ],
            'max_tokens' => 2000,
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
    public function lastConversion(Request $request)
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
                [
                    "role" => "system",
                    "content" => "  問題生成サンプルを以下に列挙します。

                    もんだい：
                    「収集」の読みかたは次のうちどれでしょうか？            
                    a) しゅうしゅう
                    b) しょうしゅう
                    c) しゅうしゅ
                    d) しゅうしょう

                    もんだい：
                    「代表」の読みかたは次のうちどれでしょうか？    
                    a) だいひょ
                    b) だいひょう
                    c) たいひょ
                    d) たいひょう

                    もんだい：
                    「友人」の読みかたは次のうちどれでしょうか？
                    a) ともだち
                    b) ゆうしん
                    c) ゆうじん
                    d) ゆうひと

                上記例を参考に、難易度や問題の作り方を模倣し、問題のみ生成してください。
                ではシミュレーションを開始します。"
                ],
                [
                    "role" => "system",
                    "content" => "日本語能力試験の「漢字読み」問題を生成します。
                答えを聞かれた場合は答えを問題ではなく生成してください"
                ],
                ["role" => "system", "content" => "userの言語ではなく日本語で話して"],
                ["role" => "system", "content" => "積極的に会話を展開してください。"],
                // ["role" => "system", "content" => "私は" . $request->language . "を話します。"],
                // ["role" => "system", "content" => "あなたは日本語で話してください"],
                // ["role" => "system", "content" => "あなたは日本語のプロです。"],
                // ["role" => "system", "content" => "出力した文章に(" . $request->language . "の翻訳)を加えてください。"],
                // ["role" => "system", "content" => "積極的に会話を展開してください。"],
                ['role' => 'user', 'content' => $request->message1],
                ["role" => "assistant", "content" => $request->result1],
                ['role' => 'user', 'content' => $request->message2],
                ["role" => "assistant", "content" => $request->result2],
                ['role' => 'user', 'content' => $request->message3],
            ],
            'max_tokens' => 2000,
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