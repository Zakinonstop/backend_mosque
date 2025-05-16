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
        $transaction = Transaction::latest()->paginate(5);

        return new ApiResource(true, 'List Transaction', $transaction);
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

        return new ApiResource(true, 'Data Transaction Berhasil Ditambahkan!', $transaction);
    }
}
