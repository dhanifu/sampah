<?php

namespace App\Exports;

use App\Models\TrashDetail;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class HistoryReport implements FromView
{
    use Exportable;

    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        $start = $this->start;
        $end = $this->end;
        $trashes = TrashDetail::select(['id', 'trash_id', 'type_id', 'weight', 'price', 'subtotal', 'created_at'])
            ->whereHas('trash', function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            })
            ->orderBy('created_at', 'ASC')
            ->with(['trash.user:id,name', 'trash.member.village:id,name'])
            ->get();

        return view('history.excel.history_excel', ['trashes' => $trashes]);
    }
}
