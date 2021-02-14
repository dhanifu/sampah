<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        return view('operator.member.create');
    }

    public function selectVillage(Request $request)
    {
        $villages = Village::select(['id','name'])->where('name', 'like', "{$request->name}%")
                    ->latest()->get()->take(5);
        $result = [];

        foreach ($villages as $village) {
            $result[] = [
                "id" => $village->id,
                "text" => $village->name,
                "name" => $village->name,
            ];
        }

        return $result;
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

        try {
            $member = Member::create([
                'name' => $request->name, 'birth_date' => $request->birth_date,
                'gender' => $request->gender, 'address' => $request->address,
                'village_id' => $request->village_id,
                'rt' => $request->rt, 'rw' => $request->rw,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', "Gagal menambah member");
        }

        if ($member->exists) {
            if ($request->previous == route('operator.transaction.data.index')) {
                $redirect = 'operator.transaction.data.index';
            } else {
                $redirect = 'operator.member.data.index';
            }
            return redirect()->route($redirect)
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
    public function edit(Member $member)
    {
        $villages = Village::orderBy('name','ASC')->get();
        return view('operator.member.edit', compact('member', 'villages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'name' => 'required|string', 'birth_date' => 'required|date',
            'gender' => 'required|string', 'village_id' => 'required|exists:villages,id',
            'rt' => 'required|min:2|max:2', 'rw' => 'required|min:2|max:2', 'address' => 'required|string'
        ]);

        if (!empty($member)) {
            try {
                $member->update([
                    'name' => $request->name, 'birth_date' => $request->birth_date,
                    'gender' => $request->gender, 'village_id' => $request->village_id,
                    'rt' => $request->rt, 'rw' => $request->rw,
                    'address' => $request->address,
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
    public function destroy(Member $member)
    {
        if (!empty($member)) {
            try {
                $member->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('error', 'Gagal menghapus. error');
            }
            return back()->with('success', 'Member dihapus');
        } else {
            return back()->with('error', 'Gagal menghapus member');
        }
    }

    // TRASH
    public function trash()
    {
        $user_uuid = Auth::user()->id;
        $members = Member::with('village')->onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('operator.member.trash', compact('members', 'user_uuid'));
    }

    public function restoreData($id)
    {
        $member = Member::onlyTrashed()->find($id);
        if ($member->exists) {
            $member->update(['deleted_by'=>null]);
            $member->restore();
            return back()->with('success', "Berhasil merestore data");
        } else {
            return back()->with('error', 'Gagal merestore data');
        }
    }

    
    public function deleteData($id)
    {
        $member = Member::onlyTrashed()->find($id);
        
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
        $member->forceDelete();

        return back()->with('success', 'Semua sampah berhasil dihapus secara permanen');
    }
}
