<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Permission;
use Modules\User\Http\Requests\PermissionRequest;
use App\Filters\PermissionFilter;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, PermissionFilter $filter)
    {
//        return response()->json(Permission::paginate(10));
        $permission = Permission::filter($filter)
            ->paginate($request->per_page);
        return response()->json($permission);
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
    public function store(PermissionRequest $request)
    {
        $permission = Permission::create($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Permission "%s" created successfully', $permission->name)
                ]
        ]);    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return response()->json($permission);
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
    public function update(Permission $permission, PermissionRequest $request)
    {
        $permission->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Permission "%s" updated successfully', $permission->name)
                ]
        ]);    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($request)
    {
//        dd($request);
//        return response($permission);
    }
}
