<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Resources\ProductResource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() {

        $products = Product::latest()->paginate(5);

        return new ProductResource(true, 'List Data Products', $products);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'         => 'required',
            'description'   => 'required',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };
    
        $image = $request->file('image');
        $image->storeAs('products', $image->hashName());
    
        $product = Product::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock,
        ]);
    
        return new ProductResource(true, 'Data Product Berhasil Ditambahkan!', $product);
    }

    public function show($id) {
        $product = Product::find($id);
        
        return new ProductResource(true, 'Detail Data Product!', $product);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'         => 'required',
            'description'   => 'required',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $product = Product::find($id);

        if ($request->hasFile('image')) {
            Storage::delete(['/products'.basename($product->image)]);

            $image = $request->file('image');
            $image->storeAs('products', $image->hashName());

            $product->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock,
            ]);

        } else {
            $product->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock,
            ]);
        };

        return new ProductResource(true, 'Data Product Berhasil Diubah!', $product);
    }

    public function destroy($id) {
        $product = Product::find($id);

        if (!$product) {
            return new ProductResource(false, 'Data kosong!', null);
        }

        Storage::delete('products/'. basename($product->image));

        $product->delete($id);

        return new ProductResource(true, 'Data berhasil dihapus!', null);
    }
}



















