<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UserService
{
    use ValidatesRequests;

    /**
     * Validate Request For Creating or Updating User
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\User $user = null
     * @return \Illuminate\Http\Response
     */
    public function validateRequest(Request $request, $user = null)
    {
        $passwords = [
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ];

        $rules = [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone_no' => 'nullable|numeric',
            'role' => [
                'required',
                Rule::in(array_keys(config('constants.roles')))
            ],
            'status' => [
                'required',
                Rule::in(array_keys(config('constants.user_status.status')))
            ],
        ];

        if (!empty($user)) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
        } else {
            $rules = array_merge($rules, $passwords);
        }

        return $this->validate($request, $rules);
    }

    /**
     * Validate Password inputs
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function validatePassword(Request $request)
    {
        return $this->validate($request, [
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);
    }

    /**
     * Create and Store new user
     *
     * @param \Illuminate\Http\Request $request
     * @return App\User $user
     */
    public function createUser(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return $user;
    }

    /**
     * Update user instance with given data
     * Images that are available will be removed and replaced with new one
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, User $user)
    {
        $input = $request->only('name', 'email', 'phone_no', 'role', 'status');

        return $user->fill($input)->save();
    }

    /**
     * Update password of a user
     *
     * @param \App\User $user
     * @param Request $request
     *
     * @return $password
     */
    public function updatePassword(Request $request, User $user)
    {
        return $user->update(['password' => Hash::make($request->password)]);
    }
}
