<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Http\Resources\ApiResource;

use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index() {
        $transaction = Transaction::latest()->paginate(10);

        return new ApiResource(true, 'Data retrieved successfully.', $transaction);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'muwaqif_id'    => 'required',
            'amount'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaction = Transaction::create([
            'muwaqif_id'    => $request->muwaqif_id,
            'amount'        => $request->amount,
        ]);

        return new ApiResource(true, 'Data created successfully.', $transaction);
    }

    public function show($id) {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return new ApiResource(false, 'Data not found!', null);
        }
        
        return new ApiResource(true, 'Data details retrieved successfully.', $transaction);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'amount'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $transaction = Transaction::find($id);

        if (!$transaction) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $transaction->update([
            'amount'        => $request->amount,
        ]);

        return new ApiResource(true, 'Data updated successfully!', $transaction);
    }

    public function destroy($id) {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $transaction->delete();

        return new ApiResource(true, 'Data deleted successfully!', null);
    }
}
