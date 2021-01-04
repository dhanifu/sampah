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
        $this->validate($request, [
            'name' => 'required|string|unique:types',
            'price' => 'required|integer'
        ]);

        $updated_by = Auth::user()->id;
        
        try {
            $type->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);

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
        $types = Type::onlyTrashed()->get();
        return view('admin.type.trash', compact('types'));
    }

    public function restoreData($id)
    {
        $type = Type::onlyTrashed()->find($id);
        $type->update(['deleted_by'=>null]);
        $type->restore();

        return back()->with('success', "Berhasil merestore data");
    }

    
    public function deleteData($id)
    {
        $type = Type::onlyTrashed()->find($id);
        $type->forceDelete();
        
        return back()->with('success', 'Berhasil menghapus permanen');
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
