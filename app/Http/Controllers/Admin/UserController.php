<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Services\UserService;

class UserController extends Controller
{
    public $userService;

    /**
     * User Service instance
     */
    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * get DataTable json.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $users = User::orderBy('id', 'desc');

        if (!empty($request->role)) {
            $users = $users->where('role', $request->role);
        }

        return DataTables::of($users)
            ->rawColumns(['name', 'email', 'status', 'actions'])
            ->editColumn('name', function ($users) {
                return $users->name;
            })
            ->editColumn('email', function ($users) {
                return '<a href="mailto:' . $users->email . '">' . $users->email . '</a>';
            })
            ->addColumn('role', function ($users) {
                return config('constants.roles')[$users->role];
            })
            ->addColumn('status', function ($users) {
                $status = config('constants.user_status.status')[$users->status];
                if ($status == 'Active') {
                    return '<span class="badge badge-success"><i class="fa fa-check"></i> ' . $status . '</span>';
                }
                return '<span class="badge badge-warning"><i class="fa fa-exclamation-circle"></i> ' . $status . '</span>';
            })
            ->editColumn('created_at', function ($users) {
                return $users->created_at->toDayDateTimeString();
            })
            ->addColumn('actions', function ($users) {
                return view('admin.users.actions', compact('users'));
            })
            ->removeColumn('updated_at')
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = config('constants.roles');
        return view('admin.users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = config('constants.roles');
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->userService->validateRequest($request);

        try {
            $user = $this->userService->createUser($request);

            return ['response' => 1, 'msg' => 'User added successfully', 'redirect' => route('admin.users.index')];
        } catch (\Throwable $e) {
            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to create user.';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.users.create')];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function view(Request $request)
    {
        return redirect(route('admin.users.show', ['user' => $request->get('user')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->userService->validateRequest($request, $user);

        try {
            $this->userService->updateUser($request, $user);
            return ['response' => 1, 'msg' => 'User updated successfully', 'redirect' => route('admin.users.index')];
        } catch (\Throwable $e) {
            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to update user.';
            return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.users.index')];
        }
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->isMethod('GET')) {
            return view('admin.users.change_password', compact('user'));
        } else {
            $this->userService->validatePassword($request);

            try {
                $this->userService->updatePassword($request, $user);

                return ['response' => 1, 'msg' => 'Password changed successfully', 'redirect' => route('admin.users.index')];
            } catch (\Exception $e) {
                if (config('app.env') === 'local') {
                    $msg = $e->getMessage();
                } else {
                    $msg = 'Failed to change password of user.';
                }

                return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.users.index')];
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            if ($user->id != auth()->user()->id) {
                $user->delete();
                return [
                    'response' => 1,
                    'msg' => 'User deleted successfully.',
                    'redirect' => route('admin.users.index')
                ];
            } else {
                throw new \Exception('You Can\'t delete Your Self');
            }
        } catch (\Exception $e) {
            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to delete user.';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.users.index')];
        }
    }
}
