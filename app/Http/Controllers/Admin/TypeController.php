<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Type;
use Auth;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $types = Type::all();
        return view('admin.type.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request["price"] = intval(str_replace(',','',$request->price));
        $this->validate($request, [
            'name' => 'required|string|unique:types',
            'price' => 'required|integer'
        ]);

        $created_by = Auth::user()->id;

        $type = Type::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        if ($type->exists) {
            return back()->with('success', 'Tipe baru berhasil disimpan');
        } else {
            return back()->with('error', 'Tipe gagal disimpan');
        }
    }

    public function update(Request $request, Type $type)
    {
        $request["price"] = intval(str_replace(',','',$request->price));
        $validation = ['price' => 'required|numeric|digits_between:0,9'];
        if ($request->name == $type->name) {
            $validation["name"] = 'required|string';
        } else {
            $validation["name"] = 'required|string|unique:types';
        }

        $this->validate($request, $validation);

        $updated_by = Auth::user()->id;
        try {
            if ($request->name == $type->name && $request->price == $type->price){
                return back()->with('success', 'Tidak mengedit apapun');
            } elseif ($request->name == $type->name) {
                $type->update([
                    'price' => $request->price,
                ]);
            }else {
                $type->update([
                    'name' => $request->name,
                    'price' => $request->price,
                ]);
            }

            return back()->with('success', 'Berhasil mengedit');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', "Gagal mengedit. error: $e");
        }
    }

    public function destroy(Type $type)
    {
        $deleted_by = Auth::user()->id;
        $type->delete();

        return back()->with('success', 'Data Type berhasil dihapus, Anda bisa mengembalikannya ditempat sampah');
    }

    
    public function trash()
    {
        $user_uuid = Auth::user()->id;
        $types = Type::onlyTrashed()->get();
        return view('admin.type.trash', compact('types', 'user_uuid'));
    }

    public function restoreData($id)
    {
        $type = Type::onlyTrashed()->find($id);
        if ($type->exists){
            $type->update(['deleted_by'=>null]);
            $type->restore();
            return back()->with('success', "Berhasil merestore data");
        } else{
            return back()->with('success', "Gagal merestore data");
        }

    }

    
    public function deleteData($id)
    {
        $type = Type::onlyTrashed()->find($id);
        if($type->exists) {
            $type->forceDelete();    
            return back()->with('success', 'Berhasil menghapus permanen');
        } else {
            return back()->with('error', 'Gagal mengahapus');
        }
    }

    public function restoreAllData($uuid)
    {
        $type = Type::onlyTrashed();
        $type->update(['deleted_by'=>null]);
        $type->restore();

        return back()->with('success', 'Berhasil merestore semua data');
    }
    
    public function deleteAllData($uuid)
    {
        $type = Type::onlyTrashed();
        $type->forceDelete();

        return back()->with('success', 'Semua sampah berhasil dihapus');
    }
}
