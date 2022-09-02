<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'contact_no' => 'nullable|numeric',
        ];

        //check password change
        if ($request->filled('password')) {
            $passwords = [
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8',
            ];
            $rules = array_merge($rules, $passwords);
        }

        $this->validate($request, $rules);

        try {

            $input = $request->only('name', 'email', 'contact_no');

            if ($request->filled('password')) {
                $input = array_merge($input, ['password' => Hash::make($request->password)]);
            }

            $updateUser = $user->fill($input)->save();

            return ['response' => 1, 'msg' => 'User updated successfully', 'redirect' => route('customer.profile.get')];
        } catch (\Throwable $e) {
            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to update user.';
            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customer.profile.get')];
        }
        return view('cusotmer.profile', compact('user'));
    }
}
