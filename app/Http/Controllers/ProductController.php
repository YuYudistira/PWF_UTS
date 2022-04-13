<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request){
        //Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:laptop,handphone',
            'price' => 'required|numeric',
            'expired_date' => 'required|date',
        ]);

        //Jika Salah
        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        //Jika Benar
        $validated = $validator->validated();

        //Simpan ke Database
        Product::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'price' => $validated['price'],
            'expired_date' => $validated['expired_date'],
        ]);
        
        //Notifikasi
        return response()->json('Berhasil Simpan')->setStatusCode(201);

    }
    
    public function show(){
        $products = Product::all();

        return response()->json($products)->setStatusCode(200);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:laptop,handphone',
            'price' => 'required|numeric',
            'expired_date' => 'required|date',
        ]);

        //Jika Salah
        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        //Jika Benar
        $validated = $validator->validated();

        //Check Data
        $CheckData = Product::find($id);

        if($CheckData){
        //Update Database
            Product::where('id',$id)
                ->update([
                    'name' => $request->name,
                    'type' => $request->type,
                    'price' => $request->price,
                    'expired_date' => $request->expired_date,
                ]);
        //Notifikasi
            return response()->json([
                'message' => 'Berhasil Update'
            ])->setStatusCode(201);
        }

        return response()->json([
            'message' => 'Data Tidak Ditemukan'
        ])->setStatusCode(404);
    }

    public function destroy($id){
        //Check Data
        $CheckData = Product::find($id);

        if($CheckData){
            Product::destroy($id);
            
            return response()->json([
                'message' => 'Data Berhasil Dihapus'
            ])->setStatusCode(200);
        }

        return response()->json([
            'message' => 'Data Tidak Ditemukan'
        ])->setStatusCode(404);
    }

}
