<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\ProfileSidebar;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('profile.edit', [
            'user'      => $user,
            'sidebar'   => ProfileSidebar::data($user),
            'pageTitle' => 'Edit Profile',
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'                     => ['required', 'string', 'max:255'],
            'email'                    => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone'                    => ['nullable', 'string', 'max:50'],
        ]);

        $user->update($data);

        return redirect()
            ->route('profile.edit')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Profile information updated successfully.',
            ]);
    }
}
