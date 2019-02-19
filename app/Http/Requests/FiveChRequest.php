<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiveChRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'test/bbs.cgi') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bbs' => 'required|string|alpha|max:8|in:min,nnp',
            'key' => 'sometimes|required|integer|digits:10',
            'subject' => 'sometimes|string|max:64',
            'FROM' => 'present|max:32',
            'mail' => 'present|max:16',
            'MESSAGE' => 'required|string|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'bbs.required' => '板キーを必ず入力してください。',
            'bbs.string' => '文字を入力してください。',
            'bbs.alpha' => 'アルファベットを入力してください。',
            'bbs.max' => '文字数が長すぎます。',
            'bbs.in' => '存在しない板です。',

            'key.required' => 'スレキーを入力してください。',
            'key.integer' => 'スレキーを入力してください。',
            'key.digits' => 'スレキーを入力してください。',


            'subject.string' => '必ずタイトルを入力してください。',
            'subject.max' => '文字数が長すぎます。',

            'FROM.present' => '文字を入力してください。',
            'FROM.max' => '文字数が長すぎます。',

            'mail.present' => '文字を入力してください。',
            'mail.max' => '文字数が長すぎます。',

            'MESSAGE.required' => '必ず本文を入力してください。',
            'MESSAGE.string' => '文字を入力してください。',
            'MESSAGE.max' => '文字数が長すぎます。',
        ];
    }
}
