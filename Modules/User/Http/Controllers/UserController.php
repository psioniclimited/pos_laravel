<?php

namespace Modules\User\Http\Controllers;

use App\Company;
use App\Filters\UserFilter;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Entities\TenantUser;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\CreateUserRequest;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, UserFilter $filters)
    {
        $users = TenantUser::join('role_user', 'users.id', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->filter($filters)
            ->groupBy('users.id', 'users.name', 'users.email', 'users.active')
            ->select('users.id', 'users.name', 'users.email', DB::raw('GROUP_CONCAT(roles.name SEPARATOR \', \') as roles_name'), 'users.active')
            ->paginate($request->per_page);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $authenticated_user = JWTAuth::parseToken()->authenticate();
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = $authenticated_user->company_id;
        $user->active = $request->active;
        $user->save();

        foreach ($request->roles as $role)
            $user->roles()->attach($role['id']);

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('User "%s" created successfully', $user->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $user = TenantUser::with('roles')
            ->where('id', $id)
            ->first();
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(User $user, Request $request)
    {
        $roles = collect(array_pluck($request->only('roles')['roles'], 'id'));

        $authenticated_user = JWTAuth::parseToken()->authenticate();
        $user->name = $request->name;
        $user->email = $request->email;
        $request->password ? $user->password = bcrypt($request->password) : '';
        $user->company_id = $authenticated_user->company_id;
        $user->active = $request->active;
        $user->save();

        $user->roles()->sync($roles);

        return response()->json([
            'update' =>
                [
                    'message' => sprintf('User "%s" updated successfully', $user->name)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
