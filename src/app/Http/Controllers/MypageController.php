<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sellingItems = $user->products()->latest()->get();

        $purchasedItems = collect();

        return view('mypage', compact('user', 'sellingItems', 'purchasedItems'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'zipcode' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->zipcode = $request->zipcode;
        $user->address = $request->address;
        $user->building = $request->building;

        $user->save();

        return redirect()->route('mypage')->with('status', 'プロフィールを更新しました！');
    }
}
