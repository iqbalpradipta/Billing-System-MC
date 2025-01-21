<?php

namespace App\Http\Controllers;

use App\Http\Resources\VpsResource;
use App\Models\vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class VpsController
{
    public function index() {
        $vps = vps::latest()->paginate(5);

        return new VpsResource(true, 'Success Get Data', $vps);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'cpu' => 'required',
            'ram' => 'required',
            'storage' => 'required',
            'price' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $vps = vps::create([
                'cpu' => $request->cpu,
                'ram' => $request->ram,
                'storage' => $request->storage,
                'price' => $request->price,
            ]);

            return new VpsResource(true, 'Success create vps', $vps);
        } catch (\Throwable $th) {
            return new VpsResource(false, 'Failed create vps', $th);
        }
    }

    public function show($id) {
        try {
            $vps = vps::find($id);

            if(!$vps) {
                return new VpsResource(false, 'Vps not found', null);
            }

            return new VpsResource(true, 'Success Get Vps', $vps);
        } catch (\Throwable $th) {
            return new VpsResource(false, 'Failed to get vps', $th->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'cpu' => 'sometimes|required',
            'ram' => 'sometimes|required',
            'storage' => 'sometimes|required',
            'price' => 'sometimes|required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vps = vps::find($id);

        if(!$vps) {
            return new VpsResource(false, 'Vps not found', null);
        }

        try {
            $vps->update($request->only(
                [
                'cpu',
                'ram',
                'storage',
                'price'
                ]
            ));

            return new VpsResource(true, 'Success update vps', $vps);
        } catch (\Throwable $th) {
            return new VpsResource(false, 'Failed update vps', $th->getMessage());
        }
    }

    public function destroy($id) {
        $vps = vps::find($id);

        if(!$vps) {
            return new VpsResource(false, 'Vps not found', null);
        }

        try {
            $vps->delete();

            return new VpsResource(true, 'Success delete vps', null);
        } catch (\Throwable $th) {
            return new VpsResource(false, 'Failed delete vps', $th->getMessage());
        }
    }

}
