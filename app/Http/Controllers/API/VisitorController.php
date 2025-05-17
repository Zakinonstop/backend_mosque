<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Http\Resources\ApiResource;

use Illuminate\Support\Facades\Validator;

class VisitorController
{
    public function index() {
        $visitor = Visitor::latest()->paginate(10);

        return new ApiResource(true, 'Data retrieved successfully.', $visitor);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visitor = Visitor::create([
            'count' => $request->count,
        ]);

        return new ApiResource(true, 'Data created successfully.', $visitor);
    }

    public function show($id) {
        $visitor = Visitor::find($id);

        if (!$visitor) {
            return new ApiResource(false, 'Data not found!', null);
        }
        
        return new ApiResource(true, 'Data details retrieved successfully.', $visitor);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'count'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $visitor = Visitor::find($id);

        if (!$visitor) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $visitor->update([
            'count'        => $request->count,
        ]);

        return new ApiResource(true, 'Data updated successfully!', $visitor);
    }

    public function destroy($id) {
        $visitor = Visitor::find($id);

        if (!$visitor) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $visitor->delete();

        return new ApiResource(true, 'Data deleted successfully!', null);
    }
}
