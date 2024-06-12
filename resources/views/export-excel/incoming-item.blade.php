<table>
    <thead>
    <tr>
        <th colspan="10" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">BARANG MASUK</th>
    </tr>
    <tr>
        <th colspan="10" style="background-color: #38BDF8; color: white; font-size: 22px; font-weight: bold;">
            @if($date)
                {{ $date }}
            @elseif($startDate && $endDate)
                {{ $startDate }} SAMPAI {{ $endDate }}
            @else
                SEMUA DATA
            @endif
        </th>
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
    @foreach($incomingItem as $row)
        <tr>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}"><img src="{{ public_path('storage/' . $row->image) }}" width="80" height="80" alt=""></td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->item_part->name }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->part_number }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->serial_number }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->status_part->name }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->status_exdismentie->name }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->site->datel->witel->name }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->site->datel->name }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->site->site_id }}</td>
            <td style="{{ $row->exit_item()->exists() ? "background-color: #EF4444; color: white;" : ""  }}">{{ $row->nuisance_ticket }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
