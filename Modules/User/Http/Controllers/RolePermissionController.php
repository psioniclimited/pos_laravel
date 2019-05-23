<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Role;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($role)
    {
        $permissions = Permission::with(['roles' => function($query) use ($role){
            $query->where('id', $role);
        }])->get();
        return response()->json($permissions);
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
    public function store($roleId, Request $request)
    {
        $role = Role::findOrFail($roleId);
        $role->perms()->sync($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Permission(s) attached to Role "%s" successfully', $role->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('user::show');
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
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
