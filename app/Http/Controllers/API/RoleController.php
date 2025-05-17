<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\ApiResource;

use Illuminate\Support\Facades\Validator;

class RoleController
{
    public function index() {
        $role = Role::latest()->paginate(10);

        return new ApiResource(true, 'Data retrieved successfully.', $role);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'name' => $request->name,
        ]);

        return new ApiResource(true, 'Data created successfully.', $role);
    }

    public function show($id) {
        $role = Role::find($id);

        if (!$role) {
            return new ApiResource(false, 'Data not found!', null);
        }
        
        return new ApiResource(true, 'Data details retrieved successfully.', $role);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $role = Role::find($id);

        if (!$role) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $role->update([
            'name'        => $request->name,
        ]);

        return new ApiResource(true, 'Data updated successfully!', $role);
    }

    public function destroy($id) {
        $role = Role::find($id);

        if (!$role) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $role->delete();

        return new ApiResource(true, 'Data deleted successfully!', null);
    }
}
