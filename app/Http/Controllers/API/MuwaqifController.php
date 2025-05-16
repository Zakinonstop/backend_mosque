<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Muwaqif;
use App\Http\Resources\ApiResource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MuwaqifController extends Controller
{
    public function index() {
        $muwaqifs = Muwaqif::latest()->paginate(5);

        return new ApiResource(true, 'List Data Muwaqif', $muwaqifs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'          => 'required',
            'email'         => 'required',
            'amount'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $image = $request->file('image');
        // $image->storeAs('muwaqifs', $image->hashName());

        $muwaqif = Muwaqif::create([
            // 'image'         => $image->hashName(),
            'name'          => $request->name,
            'email'         => $request->email,
            'amount'        => $request->amount,
        ]);

        return new ApiResource(true, 'Data Muwaqif Berhasil Ditambahkan!', $muwaqif);
    }

    public function show($id) {
        $muwaqif = Muwaqif::find($id);
        
        return new ApiResource(true, 'Detail Data Muwaqif!', $muwaqif);
    }
}
