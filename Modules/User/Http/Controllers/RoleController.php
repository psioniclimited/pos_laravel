<?php

namespace Modules\User\Http\Controllers;

use App\Filters\RoleFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Role;
use Modules\User\Http\Requests\RoleRequest;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, RoleFilter $filter)
    {
        $role = Role::filter($filter)
            ->paginate($request->per_page);
        return response()->json($role);
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
    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Role "%s" created successfully', $role->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Role $role)
    {
        return response()->json($role);
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
    public function update(Role $role, RoleRequest $request)
    {
        $role->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Role "%s" updated successfully', $role->name)
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
