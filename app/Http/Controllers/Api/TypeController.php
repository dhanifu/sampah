<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Type;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        $types = Type::select(['id','name','price'])
                ->where('name', 'like', "%{$request->name}%")
                ->orderBy('name', 'ASC')->get()->take(5);
        $result = [];

        foreach ($types as $type) {
            $result[] = [
                "id" => $type->id,
                "text" => $type->name,
                "name" => $type->name,
                "price" => $type->price
            ];
        }

        return $result;
    }

    public function show(Request $request)
    {
        return Type::select('price')->where('id', $request->id)->first();
    }
}
