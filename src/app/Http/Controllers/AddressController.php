<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        $user = Auth::user();
        $address = DeliveryAddress::firstOrNew([
            'user_id' => $user->id,
            'product_id' => $item_id,
        ]);

        return view('purchase.address_edit', compact('address', 'item_id'));
    }

    public function update(Request $request, $item_id)
    {
        $user = Auth::user();

        $request->validate([
            'zipcode' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        DeliveryAddress::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $item_id],
            [
                'zipcode' => $request->zipcode,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}

