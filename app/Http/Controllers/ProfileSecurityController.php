<?php

namespace App\Http\Controllers;

use App\Support\ProfileSidebar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileSecurityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('profile.security', [
            'sidebar'   => ProfileSidebar::data($user),
            'pageTitle' => 'Account Security',
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password does not match our records.'])
                ->with('flash', [
                    'type' => 'danger',
                    'message' => 'The current password does not match our records.',
                ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('profile.security.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Password updated successfully.',
            ]);
    }
}
