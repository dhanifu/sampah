<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

use App\Models\Trash;
use App\Models\TrashDetail;

use PDF;
use Excel;
use App\Exports\HistoryReport;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $start = $request->from_date . ' 00:00:00';
                $end = $request->to_date . ' 23:59:59';
                $histories = Trash::select(['id', 'user_id', 'member_id', 'date', 'weight', 'total'])
                    ->whereBetween('created_at', [$start, $end])
                    ->with(['user:id,name', 'member.village:id,name'])->get();
            } else {
                $histories = Trash::select(['id', 'user_id', 'member_id', 'date', 'weight', 'total'])
                    ->with(['user:id,name', 'member.village:id,name'])->get();
            }


            $datatables = Datatables::of($histories)->addIndexColumn()
                ->editColumn('operator', function ($history) {
                    return $history->user->name;
                })
                ->editColumn('member', function ($history) {
                    return $history->member->name;
                })
                ->editColumn('date', function ($history) {
                    return localDate($history->date);
                })
                ->editColumn('weight', function ($history) {
                    return $history->weight . ' Kg';
                })
                ->editColumn('total', function ($history) {
                    return 'Rp ' . number_format($history->total);
                })
                ->addColumn('action', function ($history) {
                    $detail = route('transaction.history.detail') . '?date=' . $history->date;
                    return '
                        <div class="btn-group" role="group" aria-label="Action">
                            <a class="btn btn-info btn-sm"
                                    href="' . $detail . '">
                                <i class="fas fa-info-circle"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" id="remove" data-id="' . $history->id . '">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    ';
                })->rawColumns(['action'])->make();

            return $datatables;
        }

        return view('history.index');
    }

    public function detail(Request $request)
    {
        $data = TrashDetail::select(['id', 'trash_id', 'type_id', 'weight', 'price', 'subtotal', 'created_at'])
            ->where('created_at', $request->date)
            ->with(['trash.user', 'trash.member.village'])
            ->get();

        $transaction = Trash::select(['id', 'user_id', 'member_id', 'date', 'weight', 'total'])
            ->where('date', $request->date)
            ->with(['user:id,name', 'member:id,name'])
            ->firstOrFail();
        // dd($trash);

        return view('history.detail', compact('data', 'transaction'));
    }

    public function destroy(Trash $trash)
    {
        try {
            TrashDetail::select('trash_id')->where('trash_id', $trash->id)->delete();
            $trash->delete();
            $trashCount = Trash::onlyTrashed()->count();

            return response()->json([
                'success' => 'berhasil dihapus ke tempat sampah',
                'count' => $trashCount
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'gagal menghapus']);
        }
    }


    // TRASH
    public function trash(Request $request)
    {

        if ($request->ajax()) {
            $histories = Trash::onlyTrashed()->select(['id', 'user_id', 'member_id', 'date', 'weight', 'total'])
                ->latest()->with(['user:id,name', 'member.village:id,name'])
                ->get();

            $datatables = Datatables::of($histories)->addIndexColumn()
                ->editColumn('operator', function ($history) {
                    return $history->user->name;
                })
                ->editColumn('member', function ($history) {
                    return $history->member->name;
                })
                ->editColumn('date', function ($history) {
                    return localDate($history->date);
                })
                ->editColumn('weight', function ($history) {
                    return $history->weight . ' Kg';
                })
                ->editColumn('total', function ($history) {
                    return 'Rp ' . number_format($history->total);
                })
                ->addColumn('action', function ($history) {
                    $detail = route('transaction.history.detail') . '?date=' . $history->date;
                    return '
                        <div class="btn-group" role="group" aria-label="Action">
                            <button type="button" class="btn btn-info btn-sm" title="Restore"
                                    data-action="restore">
                                <i class="fas fa-redo-alt fa-flip-horizontal"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Delete Permanent"
                                    data-action="remove">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    ';
                })->rawColumns(['action'])->make();

            return $datatables;
        }
        return view('history.trash');
    }

    public function restoreData($id)
    {
        $trash = Trash::onlyTrashed()->findOrFail($id);
        $trash_detail = TrashDetail::onlyTrashed()->where('trash_id', $trash->id);

        try {
            if ($trash->exists && !empty($trash_detail)) {
                // Update deleted_by menjadi null
                $trash_detail->update(['deleted_by' => null]);
                $trash->update(['deleted_by' => null]);

                // Restore datanya
                $trash_detail->restore();
                $trash->restore();
                $trashCount = Trash::onlyTrashed()->count();

                return response()->json([
                    'success' => "Berhasil merestore data",
                    'count' => $trashCount
                ]);
            } else {
                $trashCount = Trash::onlyTrashed()->count();
                return response()->json([
                    'error' => "Gagal merestore data",
                    'count' => $trashCount
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $trashCount = Trash::onlyTrashed()->count();
            return response()->json([
                'error' => "Gagal merestore data",
                'count' => $trashCount
            ]);
        }
    }

    public function deleteData($id)
    {
        $trash = Trash::onlyTrashed()->findOrFail($id);
        $trash_detail = TrashDetail::onlyTrashed()->where('trash_id', $trash->id);

        try {
            if ($trash->exists && !empty($trash_detail)) {
                $trash_detail->forceDelete();
                $trash->forceDelete();
                $trashCount = Trash::onlyTrashed()->count();

                return response()->json([
                    'success' => "Berhasil menghapus data permanen",
                    'count' => $trashCount
                ]);
            } else {
                $trashCount = Trash::onlyTrashed()->count();
                return response()->json([
                    'error' => "Gagal menghapus",
                    'count' => $trashCount
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $trashCount = Trash::onlyTrashed()->count();
            return response()->json([
                'error' => "Gagal menghapus. Kesalahan tidak diketahui",
                'count' => $trashCount
            ]);
        }
    }

    public function restoreAllData()
    {
        $trash = Trash::onlyTrashed();
        $trash_detail = TrashDetail::onlyTrashed();

        try {
            $trash_detail->update(['deleted_by' => null]);
            $trash->update(['deleted_by' => null]);
            $trash_detail->restore();
            $trash->restore();
            $trashCount = Trash::onlyTrashed()->count();

            return response()->json([
                'success' => 'Berhasil merestore semua data',
                'count' => $trashCount
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $trashCount = Trash::onlyTrashed()->count();
            return response()->json([
                'error' => 'Gagal Menghapus.',
                'count' => $trashCount
            ]);
        }
    }

    public function deleteAllData()
    {
        $trash_detail = TrashDetail::onlyTrashed();
        $trash = Trash::onlyTrashed();

        try {
            $trash_detail->forceDelete();
            $trash->forceDelete();
            $trashCount = Trash::onlyTrashed()->count();

            return response()->json([
                'success' => "Berhasil menghapus semua data secara permanen",
                'count' => $trashCount
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $trashCount = Trash::onlyTrashed()->count();
            return response()->json([
                'error' => "Gagal menghapus data",
                'count' => $trashCount
            ]);
        }
    }

    public function historyPdf($daterange)
    {
        $date = explode('+', $daterange);

        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $trashes = TrashDetail::select(['id', 'trash_id', 'type_id', 'weight', 'price', 'subtotal', 'created_at'])
            ->whereHas('trash', function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            })->orderBy('created_at', 'ASC')
            ->with(['trash.user', 'trash.member.village'])
            ->get();

        // buat test tampilan
        // return view('history.pdf.history_pdf', compact('trashes', 'date'));

        $pdf = PDF::loadView('history.pdf.history_pdf', compact('trashes', 'date'))
            ->setPaper('A4', 'potrait');

        // return $pdf->stream();
        return $pdf->download('history-transaksi_' . $date[0] . '-' . $date[1] . '.pdf');
    }

    function tanggal(string $date): String
    {
        return date('Y-m-d', strtotime($date));
    }
    function waktu(string $time): String
    {
        return date('H-i', strtotime($time));
    }

    public function detailPdf($date)
    {
        $data = TrashDetail::select(['id', 'trash_id', 'type_id', 'weight', 'price', 'subtotal', 'created_at'])
            ->where('created_at', $date)
            ->with(['trash.user', 'trash.member.village'])
            ->get();
        $transaction = Trash::select(['id', 'user_id', 'member_id', 'date', 'weight', 'total'])
            ->where('date', $date)
            ->with(['user:id,name', 'member.village:id,name'])
            ->firstOrFail();

        // return view('history.pdf.detail_pdf', compact('data', 'date', 'transaction'));

        $pdf = PDF::loadView('history.pdf.detail_pdf', compact('data', 'date', 'transaction'))
            ->setPaper('A4', 'potrait');
        $tanggal = $this->tanggal($date);
        $waktu = $this->waktu($date);
        // return $pdf->stream();
        return $pdf->download("detail-transaksi_tanggal($tanggal)_jam($waktu).pdf");
    }

    public function historyExcel($daterange)
    {
        $date = explode('+', $daterange);

        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $history = new HistoryReport($start, $end);
        return $history->download('history-transaksi_' . $date[0] . '-' . $date[1] . '.xlsx');
    }
}
