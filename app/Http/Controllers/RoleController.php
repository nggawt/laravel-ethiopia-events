<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|unique:roles,name|string|min:3|max:72',
            'slug' => 'required|string|min:3|max:90',
            'permissions' => 'required|array',
            'permissions.*' => 'required|boolean',
        ]);

        // $validated['permissions'] = json_encode($validated['permissions']);
        return $validated;
        $newRole = Role::create($validated);
        return ['response' => $request->all(), 'newRole' => $newRole];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|exists:roles,name|string|min:3|max:72',
            'slug' => 'required|string|min:3|max:90',
            'up' => 'required_without:dl|array',
            'up.*' => 'boolean',
            'dl' => 'required_without:up|array',
            'dl.*' => 'boolean',
        ])->validate();

        // $validator['permissions'] = json_encode($validator['permissions']);
        // return $validator;
        
        if(isset($validator['dl']) && count($validator['dl'])) $role['permissions'] = collect($role['permissions'])->except(array_keys($validator['dl']));
        if(isset($validator['up']) && count($validator['up'])) $role['permissions'] = collect($role['permissions'])->merge($validator['up']);

        // $ex = collect($role)->except(array_keys($validator['dl'])[0]);
        return $role;
        
        return ['role' => $role];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
