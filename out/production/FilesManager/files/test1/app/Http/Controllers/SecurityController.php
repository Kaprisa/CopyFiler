<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function saveRole(Request $request)
    {
        $check = $request->has('id') ? [ 'id' => $request->id ] : [ 'name' => $request->name ];
        $role = Role::updateOrCreate($check, [
           'name' => $request->name,
           'display_name' => $request->display_name
       ]);
       return response()->json($role, 200);
    }

    public function removeRole($id)
    {
        Role::destroy($id);
        return null;
    }
}
