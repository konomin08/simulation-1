<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class SellController extends Controller
{
    public function create()
    {
        $categories = [
            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ',
            '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'
        ];
        $conditions = ['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'];

        return view('sell', compact('categories', 'conditions'));
    }

    /**
     * プレビュー：画像を一時保存（storage/app/public/tmp）→ フォームに戻す
     */
    public function preview(Request $request)
    {
        $request->validate([
            'image' => ['required','file','mimes:jpeg,png','max:5120'],
        ], [
            'image.required' => 'プレビューには画像の選択が必要です。',
            'image.mimes'    => 'JPEG もしくは PNG を選択してください。',
        ]);

        $tmpPath = $request->file('image')->store('tmp', 'public');

        return redirect()
            ->route('sell.create')
            ->withInput($request->except('image'))
            ->with(['previewPath' => $tmpPath]);
    }

    /**
     * 本保存：新規image か temp_image（プレビュー済み）のどちらかで保存OK
     */
    public function store(ExhibitionRequest $request)
    {
        $v = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        } else {
            $tmp = $v['temp_image'] ?? null;
            if (!$tmp || !Storage::disk('public')->exists($tmp)) {
                return back()->withErrors(['image' => 'プレビュー画像が見つかりません。もう一度アップロードしてください。'])
                             ->withInput();
            }
            $path = 'products/'.basename($tmp);
            Storage::disk('public')->move($tmp, $path);
        }

        $product = Product::create([
            'name'        => $v['name'],
            'description' => $v['description'],
            'price'       => $v['price'],
            'condition'   => $v['condition'],
            'image_path'  => $path,
            'user_id'     => auth()->id(),
        ]);

        $names = collect($v['categories'] ?? [])
                    ->filter()
                    ->unique()
                    ->values();

        $categoryIds = $names->map(function ($name) {
            return Category::firstOrCreate(['name' => $name])->id;
        })->all();

        $product->categories()->sync($categoryIds);

        return redirect()->route('sell.complete');
    }
    public function complete()
    {
        return view('sell_complete');
    }
}
