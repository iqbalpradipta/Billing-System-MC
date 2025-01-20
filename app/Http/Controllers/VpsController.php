<?php

namespace App\Http\Controllers;

use App\Http\Resources\VpsResource;
use App\Models\vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VpsController
{
    public function index() {
        $vps = vps::latest()->pagination(5);

        return new VpsResource(true, 'Success Get Data', $vps);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'cpu' => 'required',
            'ram' => 'required',
            'storage' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $vps = vps::create([
                'cpu' => $request->cpu,
                'ram' => $request->ram,
                'storage' => $request->storage
            ]);

            return new VpsResource(true, 'Success create vps', $vps);
        } catch (\Throwable $th) {
            return new VpsResource(true, 'Failed create vps', $th);
        }
    }

}
