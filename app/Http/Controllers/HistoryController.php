<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Trash;
use App\Models\TrashDetail;

class HistoryController extends Controller
{
    public function index()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d h:i:s');

        if ( request()->date != '' ) {
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d'). ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d'). ' 23:59:59';
        }

        $histories = Trash::select(['id','user_id','member_id','date','weight','total'])
                    ->whereBetween('created_at', [$start, $end])
                    ->with(['user:id,name','member.village:id,name'])->paginate(10);
        
        return view('operator.transaction.history.index', compact('histories'));
    }

    public function detail(Request $request)
    {
        $data = TrashDetail::select(['id','trash_id','type_id','weight','price','subtotal','created_at'])
                ->where('created_at', $request->date)
                ->with(['trash.user', 'trash.member.village'])
                ->get();

        $transaction = Trash::select(['id','user_id','member_id','date','weight','total'])
                        ->where('date', $request->date)
                        ->with(['user:id,name', 'member:id,name'])
                        ->firstOrFail();
        // dd($trash);

        return view('operator.transaction.history.detail', compact('data','transaction'));
    }

    public function destroy(Trash $trash)
    {
        try {
            TrashDetail::select('trash_id')->where('trash_id',$trash->id)->delete();
            $trash->delete();

            return back()->with('success', 'berhasil dihapus ke tempat sampah');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'gagal menghapus');
        }
    }


    // TRASH
    public function trash()
    {
        $histories = Trash::onlyTrashed()->select(['id','user_id','member_id','date','weight','total'])
                        ->latest()->with(['user:id,name','member.village:id,name'])
                        ->paginate(10);
        return view('operator.transaction.trash', compact('histories'));
    }

    public function restoreData($id)
    {
        // 
    }

    public function deleteData($id)
    {
        // 
    }
}
