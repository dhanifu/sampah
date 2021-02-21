<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Operator</th>
            <th>Member</th>
            <th>Alamat</th>
            <th>Tanggal</th>
            <th>Tipe</th>
            <th>Weight</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    @php
        $total = 0;
        $total_weight = 0;
    @endphp
    @foreach($trashes as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->trash->user->name }}</td>
            <td>{{ $row->trash->member->name }}</td>
            <td>{{ $row->trash->member->address }}, RT.{{ $row->trash->member->rt }}/RW.{{ $row->trash->member->rw }}, Ds.{{ $row->trash->member->village->name }}</td>
            <td>{{ localDateTime($row->trash->date) }}</td>
            <td>{{ $row->type->name }}</td>
            <td>{{ $row->weight }} Kg</td>
            <td>Rp {{ number_format($row->subtotal) }}</td>
        </tr>
        @php
            $total_weight += $row->weight;
            $total += $row->subtotal;
        @endphp
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" align="right"><strong>Subtotal</strong></td>
            <td>{{ $total_weight }} Kg</td>
            <td>Rp {{ number_format($total) }}</td>
        </tr>
    </tfoot>
</table>