<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Village;
use Auth;

class VillageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $villages = Village::orderBy('created_at', 'DESC')->get();
        return view('admin.village.index', compact('villages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,['name'=>'required|string']);

        $village = Village::create([
            'name' => $request->name,
        ]);

        if ($village->exists) {
            return back()->with('success', 'Village berhasil ditambah!');
        } else {
            return back()->with('error', 'Village gagal ditambah');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Village $village)
    {
        $this->validate($request, ['name'=>'required|string']);

        try {
            $village->update([
                'name' => $request->name
            ]);

            return back()->with('success', 'Village berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Village gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function destroy(Village $village)
    {
        try {
            $village->delete();

            return back()->with('success', 'Village berhasil dihapus ke Recycle Bin');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Village gagal dihapus');
        }
    }


    // TRASH
    public function trash()
    {
        $user_uuid = Auth::user()->id;
        $villages = Village::onlyTrashed()->get();
        return view('admin.village.trash', compact('villages', 'user_uuid'));
    }

    public function restoreData($id)
    {
        $village = Village::onlyTrashed()->find($id);
        if ($village->exists){
            $village->update(['deleted_by'=>null]);
            $village->restore();
            return back()->with('success', "Berhasil merestore data");
        } else {
            return back()->with('success', "Gagal merestore data");
        }
    }
    
    public function deleteData($id)
    {
        $village = Village::onlyTrashed()->find($id);
        if($village->exists){
            $village->forceDelete();
            return back()->with('success', 'Berhasil menghapus permanen');
        } else {
            return back()->with('error', 'Gagal mengahapus');
        }
    }

    public function restoreAllData($uuid)
    {
        $village = Village::onlyTrashed();
        $village->update(['deleted_by'=>null]);
        $village->restore();

        return back()->with('success', 'Berhasil merestore semua data');
    }
    
    public function deleteAllData($uuid)
    {
        $village = Village::onlyTrashed();
        $village->forceDelete();

        return back()->with('success', 'Semua sampah berhasil dihapus');
    }
}
