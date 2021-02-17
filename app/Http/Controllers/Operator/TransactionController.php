<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use DB;

use Auth;
use App\Models\Member;
use App\Models\Type;
use App\Models\Trash;
use App\Models\TrashDetail;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('operator.transaction.index');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $error = Validator::make($input, ['member_id'  => 'required|exists:members,id']);
        if($error->fails())
        {
            return response()->json(['error'  => $error->errors()->all()]);
        }

        DB::beginTransaction();
        try {
            $trash = Trash::create([
                'member_id' => $input['member_id'],
                'user_id' => Auth::user()->id,
                'date' => Carbon::now(),
                'weight' => 0,
                'total' => 0
            ]);
            $date = Trash::select('date')->where('date', Carbon::now())->first();

            $total = 0;
            $weight = 0.00;
            
            foreach($input['transactions'] as $detail) {
                $total += $detail['subtotal'];
                $weight += $detail['weight'];

                TrashDetail::create([
                    'trash_id' => $trash->id,
                    'type_id' => $detail['type_id'],
                    'price' => $detail['price'],
                    'weight' => $detail['weight'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            $trash->update(['weight' => $weight]);
            $trash->update(['total' => $total]);

            DB::commit();
            return response()->json(['success' => 'Transaksi Sukses', 'tanggal' => $date->date]);
        } catch(\Exception $e) {

            DB::rollback();
            return response()->json(['error' => 'Transaksi Gagal']);
        }
    }
}
