<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\ProfileSidebar;

class ProfilePaymentController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('profile.payment', [
            'user' => $user,
            'sidebar' => ProfileSidebar::data($user),
            'pageTitle' => 'Informasi Pembayaran',
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'payment_account_name'   => ['nullable', 'string', 'max:100'],
            'payment_card_number'    => ['nullable', 'string', 'max:25'],
            'payment_card_expiry'    => ['nullable', 'string', 'max:7'],
            'payment_card_cvc'       => ['nullable', 'string', 'max:4'],
        ]);

        $request->user()->update($data);

        return redirect()
            ->route('profile.payment.edit')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Informasi pembayaran berhasil disimpan.',
            ]);
    }
}
