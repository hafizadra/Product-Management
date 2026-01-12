<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use App\Support\ProfileSidebar;
use Illuminate\Http\Request;

class ProfileAddressController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('profile.addresses', [
            'addresses' => $user->addresses()
                ->orderByDesc('is_default')
                ->latest()
                ->get(),
            'sidebar'   => ProfileSidebar::data($user),
            'pageTitle' => 'Address Book',
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $this->validateAddress($request);
        $data['is_default'] = $request->boolean('is_default');

        $address = $user->addresses()->create($data);

        if ($data['is_default'] || $user->addresses()->count() === 1) {
            $this->markAsDefault($user->id, $address);
        }

        return redirect()
            ->route('profile.addresses.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Address added successfully.',
            ]);
    }

    public function update(Request $request, UserAddress $address)
    {
        $user = $request->user();
        $this->ensureOwner($address, $user->id);

        $data = $this->validateAddress($request, includeDefault: false);

        $address->update($data);

        return redirect()
            ->route('profile.addresses.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Address updated successfully.',
            ]);
    }

    public function destroy(Request $request, UserAddress $address)
    {
        $user = $request->user();
        $this->ensureOwner($address, $user->id);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextDefault = $user->addresses()->latest()->first();
            if ($nextDefault) {
                $this->markAsDefault($user->id, $nextDefault);
            } else {
                $user->update(['default_shipping_address' => null]);
            }
        }

        return redirect()
            ->route('profile.addresses.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Address deleted successfully.',
            ]);
    }

    public function makeDefault(Request $request, UserAddress $address)
    {
        $user = $request->user();
        $this->ensureOwner($address, $user->id);

        $this->markAsDefault($user->id, $address);

        return redirect()
            ->route('profile.addresses.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Default address updated.',
            ]);
    }

    protected function validateAddress(Request $request, bool $includeDefault = true): array
    {
        $rules = [
            'label'          => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'city'           => ['nullable', 'string', 'max:100'],
            'postal_code'    => ['nullable', 'string', 'max:20'],
            'address_line'   => ['required', 'string', 'max:600'],
        ];

        if ($includeDefault) {
            $rules['is_default'] = ['sometimes', 'boolean'];
        }

        return $request->validate($rules);
    }

    protected function ensureOwner(UserAddress $address, int $userId): void
    {
        abort_if($address->user_id !== $userId, 403);
    }

    protected function markAsDefault(int $userId, UserAddress $address): void
    {
        UserAddress::where('user_id', $userId)
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);
        $address->user()->update([
            'default_shipping_address' => $address->address_line,
        ]);
    }
}
