<table>
    <thead>
    <tr>
        <th colspan="10" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">DAFTAR PART</th>
    </tr>
    <tr>
        <th colspan="10" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">{{ strtoupper($namaUser) }}</th>
    </tr>
    <tr>
        <th colspan="10" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('j F Y') }}</th>
    </tr>
    <tr>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Gambar Part</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Nama Part</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Part Number</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Serial Number</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Status Part</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Status Exdismentie</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Witel</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Datel</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Site ID</th>
        <th style="background-color: #0C4A6E; color: white; font-size: 16px; font-weight: bold;">Tiket Gangguan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($exitItem as $row)
        <tr>
            <td><img src="{{ public_path('storage/' . $row->image) }}" width="80" height="80"></td>
            <td>{{ $row->incoming_item->item_part->name }}</td>
            <td>{{ $row->incoming_item->part_number }}</td>
            <td>{{ $row->incoming_item->serial_number }}</td>
            <td>{{ $row->incoming_item->status_part->name }}</td>
            <td>{{ $row->incoming_item->status_exdismentie->name }}</td>
            <td>{{ $row->site->datel->witel->name }}</td>
            <td>{{ $row->site->datel->name }}</td>
            <td>{{ $row->site->site_id }}</td>
            <td>{{ $row->nuisance_ticket }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
