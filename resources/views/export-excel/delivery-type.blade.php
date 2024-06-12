<table>
    <tr>
        <td>LS =</td>
        @php
            $mount_count = 0;
        @endphp
        @foreach ($exitItemCountByMonth as $count)
            @php
                $mount_count += $count;
            @endphp
        @endforeach
        <td>{{ round((2 * $mount_count * $witel->delivery_type->price / 12) / 12) }}</td>
    </tr>
    <tr>
        <td>SS =</td>
        <td>28</td>
    </tr>
    <tr>
        <td>LT =</td>
        <td>1</td>
    </tr>
    <tr>
        <td>PHO =</td>
        <td>10</td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">No</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Kategori</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Harga Sebagai
            Presentasi Nilai Persediaan
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>Administrasi</td>
        <td>2%</td>
    </tr>
    <tr>
        <td>2</td>
        <td>Resiko Kerusakan</td>
        <td>2%</td>
    </tr>
    <tr>
        <td>3</td>
        <td>Asuransi</td>
        <td>1%</td>
    </tr>
    <tr>
        <td colspan="2">Total Biaya Persediaan</td>
        <td>5%</td>
    </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Periode</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Past Due</th>
        @foreach($allMonths as $month)
            <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">{{ $month }}</th>
        @endforeach
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>Gross requrements (GR)</td>
        <td>0</td>
        @foreach ($exitItemCountByMonth as $count)
            <td>{{ $count }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Scheduled Recelpts (SR)</td>
        <td>0</td>
        @foreach ($exitItemCountByMonth as $count)
            <td>0</td>
        @endforeach
    </tr>
    <tr>
        @php
            $mount_count = 0;
        @endphp
        @foreach ($exitItemCountByMonth as $count)
            @php
                $mount_count += $count;
            @endphp
        @endforeach
        <td>Projected On-hand (POH)</td>
        <td>10</td>
        @foreach ($exitItemCountByMonth as $count)
            <td>{{ 10 + round(((2 * $mount_count * $witel->delivery_type->price / 12) / 12)) + $count}}</td>
        @endforeach
    </tr>
    <tr>
        <td>Net Requirements (NR)</td>
        <td>0</td>
        @foreach ($exitItemCountByMonth as $count)
            <td>{{ ($count + 28) - 10 }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Planned Order Receipts</td>
        <td>0</td>
        @php
            $mount_count = 0;
        @endphp
        @foreach ($exitItemCountByMonth as $count)
            @php
                $mount_count += $count;
            @endphp
        @endforeach
        @foreach ($exitItemCountByMonth as $count)
            <td>{{ round((2 * $mount_count * $witel->delivery_type->price / 12) / 12) }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Planned Order Releases</td>
        <td>0</td>
        @php
            $mount_count = 0;
        @endphp
        @foreach ($exitItemCountByMonth as $count)
            @php
                $mount_count += $count;
            @endphp
        @endforeach
        @foreach ($exitItemCountByMonth as $count)
            <td>{{ round((2 * $mount_count * $witel->delivery_type->price / 12) / 12) }}</td>
        @endforeach
    </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">No</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Kota</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Frekuensi Kirim</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Biaya Kirim</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Total Biaya Kirim</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Total Biaya Simpan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($witelsWithExitItemCount as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item['witel']->name }}</td>
            <td>{{ $item['exitItemCount'] }}</td>
            <td>Rp. {{number_format($item['witel']->delivery_type->price) }}</td>
            <td>Rp. {{number_format($item['witel']->delivery_type->price * $item['exitItemCount']) }}</td>
            <td>Rp. {{number_format(($item['witel']->delivery_type->price * (0.42 / 100)) / 2 * $item['exitItemCount']) }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4">Total</td>
        <td>Rp. {{ $total_shipping_costs }}</td>
        <td>Rp. {{ $total_holding_cost }}</td>
    </tr>
    <tr>
        <td colspan="4">Total Keseluruhan</td>
        <td colspan="2">Rp. {{ $total_shipping_costs +  $total_holding_cost }}</td>
    </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th colspan="5" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">Perhitungan DRP</th>
    </tr>
    <tr>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">No</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Kota</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Frekuensi Kirim</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Biaya Kirim</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_drp = 0;
    @endphp
    @foreach($witelsWithExitItemCount as $item)
        @php
            if ($item['exitItemCount'] !== 0) {
                $total_drp += $item['witel']->delivery_type->price + round(((2 + $item['exitItemCount'] + $item['witel']->delivery_type->price / 12) / 12) / $item['exitItemCount']);
            }
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item['witel']->name }}</td>
            @if ($item['exitItemCount'] !== 0)
                <td>{{ $item['exitItemCount'] }}</td>
            @else
                <td>0</td>
            @endif
            <td>Rp. {{ $item['witel']->delivery_type->price }}</td>
            @if ($item['exitItemCount'] !== 0)
                <td>
                    Rp. {{ number_format($item['exitItemCount'] * $item['witel']->delivery_type->price, 0, ',', '.') }}</td>
            @else
                <td>Rp. 0</td>
            @endif
        </tr>
    @endforeach
    <tr>
        <td colspan="4">Total</td>
        <td>{{ $total_drp }}</td>
    </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="3" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">Perhitungan DRP</th>
        </tr>
        <tr>
            <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Kota</th>
            <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Total Persediaan</th>
            <th style="background-color: #0C4A6E; color: white; font-size: 12px; font-weight: bold;">Total Biaya Simpan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $mount_count_drp = 0;
            $value_total_persediaan = 0;
        @endphp
        @foreach ($exitItemCountByMonth as $count)
            @php
                $mount_count_drp += $count;
            @endphp
        @endforeach

        @foreach ($exitItemCountByMonth as $count)
            @php
                $value_total_persediaan += 10 + round((2 + $mount_count_drp + $witel->delivery_type->price / 12) / 12) + $count
            @endphp
        @endforeach

        <tr>
            <td>{{ $witel->name }}</td>
            <td>{{ $value_total_persediaan }}</td>
            <td>{{ $value_total_persediaan + $mount_count_drp }}</td>
        </tr>
        <tr>
            <td>Perusahaan</td>
            <td>DRP</td>
        </tr>
        <tr>
            <td>Rp. {{ number_format($total_shipping_costs +  $total_holding_cost, 0, ',', '.') }}</td>
            <td>Rp. {{  number_format($value_total_persediaan + ($witel->delivery_type->price + (0.42 / 100 )) / 2 + $mount_count_drp + $total_drp) }}</td>
        </tr>
    </tbody>
</table>
