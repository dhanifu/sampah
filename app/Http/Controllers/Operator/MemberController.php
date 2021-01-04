<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Village;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::with('village')->orderBy('created_at', 'DESC')->get();
        return view('operator.member.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $villages = Village::orderBy('name','ASC')->get();
        return view('operator.member.create', compact('villages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string', 'birth_date' => 'required|date',
            'gender' => 'required|string', 'village_id' => 'required|exists:villages,id',
            'rt' => 'required', 'rw' => 'required', 'address' => 'required|string'
        ]);

        $uuid = Uuid::uuid4();
        $created_by = Auth::user()->uuid;

        try {
            $member = Member::create([
                'uuid' => $uuid, 'name' => $request->name,
                'birth_date' => $request->birth_date, 'gender' => $request->gender,
                'address' => $request->address, 'village_id' => $request->village_id,
                'rt' => $request->rt, 'rw' => $request->rw,
                'created_by' => $created_by
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', "Gagal menambah member, error: tidak diketahui");
        }

        if ($member->exists) {
            return redirect()->route('operator.member.data.index')
                            ->with('success', 'Berhasil menambah member baru');
        } else {
            return redirect()->back()->with('error', 'Gagal menambah member baru');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $member = Member::with('village')->where('uuid', $uuid)->orderBy('created_at', 'DESC')->first();
        $villages = Village::orderBy('name','ASC')->get();
        return view('operator.member.edit', compact('uuid', 'member', 'villages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $this->validate($request, [
            'name' => 'required|string', 'birth_date' => 'required|date',
            'gender' => 'required|string', 'village_id' => 'required|exists:villages,id',
            'rt' => 'required', 'rw' => 'required', 'address' => 'required|string'
        ]);

        $updated_by = Auth::user()->uuid;
        $member = Member::where('uuid', $uuid)->first();

        if (!empty($member)) {
            try {
                $member->update([
                    'name' => $request->name, 'birth_date' => $request->birth_date,
                    'gender' => $request->gender, 'village_id' => $request->village_id,
                    'rt' => $request->rt, 'rw' => $request->rw,
                    'address' => $request->address, 'updated_by' => $updated_by
                ]);
                return redirect()->route('operator.member.data.index')->with('success', 'Berhasil mengedit member');
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect()->back()->with('error', "Gagal mengedit member, error: tidak diketahui");
            }
        } else {
            return redirect()->route('operator.member.data.index')->with('error', 'Gagal mengedit member');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $deleted_by = Auth::user()->uuid;
        $member = Member::where('uuid', $uuid)->first();

        if (!empty($member)) {
            try {
                $member->update(['deleted_by'=>$deleted_by]);
                $member->delete();
                return back()->with('success', 'Member dihapus');
            } catch (\Throwable $th) {
                return back()->with('error', '');
            }
        } else {
            return back()->with('error', 'Gagal menghapus member');
        }
    }

    // TRASH
    public function trash()
    {
        $user_uuid = Auth::user()->uuid;
        $members = Member::with('village')->onlyTrashed()->get();
        return view('operator.member.trash', compact('members', 'user_uuid'));
    }

    public function restoreData($uuid)
    {
        $member = Member::onlyTrashed()->where('uuid', $uuid)->first();
        if ($member->exists) {
            $member->update(['deleted_by'=>null]);
            $member->restore();
            return back()->with('success', "Berhasil merestore data");
        } else {
            return back()->with('error', 'Gagal merestore data');
        }
    }

    
    public function deleteData($uuid)
    {
        $member = Member::onlyTrashed()->where('uuid', $uuid)->first();
        
        if ($member->exists) {
            $member->update(['deleted_by'=>null]);
            $member->forceDelete();
            return back()->with('success', 'Berhasil menghapus secara permanen');
        } else {
            return back()->with('error', 'Gagal mengahapus');
        }
    }

    public function restoreAllData($uuid)
    {
        $member = Member::onlyTrashed();
        $member->update(['deleted_by'=>null]);
        $member->restore();

        return back()->with('success', 'Berhasil merestore semua data');
    }
    
    public function deleteAllData($uuid)
    {
        $member = Member::onlyTrashed();
        $member->update(['deleted_by'=>null]);
        $member->forceDelete();

        return back()->with('success', 'Semua sampah berhasil dihapus secara permanen');
    }
}
