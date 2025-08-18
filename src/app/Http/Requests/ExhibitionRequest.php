<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'categories'  => ['required', 'array', 'min:1'],
            'categories.*'=> ['string'],
            'condition'   => ['required', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],

            'image'       => ['nullable', 'file', 'mimes:jpeg,png', 'max:5120', 'required_without:temp_image'],
            'temp_image'  => ['nullable', 'string', 'required_without:image'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'description.max'      => '商品説明は255文字以内で入力してください。',
            'categories.required'  => '商品のカテゴリーを選択してください。',
            'categories.min'       => '商品のカテゴリーを1つ以上選択してください。',
            'condition.required'   => '商品の状態を選択してください。',
            'price.required'       => '商品価格を入力してください。',
            'price.numeric'        => '商品価格は数値で入力してください。',
            'price.min'            => '商品価格は0円以上で入力してください。',

            'image.required_without'  => 'プレビュー画像がない場合は商品画像をアップロードしてください。',
            'image.mimes'             => '商品画像は.jpegまたは.png形式でアップロードしてください。',
            'image.max'               => '商品画像のサイズは5MB以下にしてください。',
            'temp_image.required_without' => '商品画像をアップロードしていない場合はプレビュー画像が必要です。',
        ];
    }
}
