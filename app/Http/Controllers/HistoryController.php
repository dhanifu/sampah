<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Trash;
use App\Models\TrashDetail;

class HistoryController extends Controller
{
    public function index()
    {
        return view('operator.transaction.history.index');
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

}
