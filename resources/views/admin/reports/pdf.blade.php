<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan</title>
    <style>
        @page { margin: 22px 22px 46px 22px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        .muted { color: #64748b; }
        .small { font-size: 10px; }
        .h1 { font-size: 18px; font-weight: 900; letter-spacing: .3px; }
        .pill { display: inline-block; padding: 6px 10px; border-radius: 999px; background: #eef2ff; color: #3730a3; font-weight: 800; font-size: 10px; }
        .card { border: 1px solid #e5e7eb; border-radius: 14px; padding: 12px; }
        .spacer { height: 10px; }
        .kpi-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .kpi-box { border: 1px solid #e5e7eb; border-radius: 14px; padding: 12px; }
        .kpi-label { color: #64748b; font-size: 10px; font-weight: 800; letter-spacing: .3px; text-transform: uppercase; }
        .kpi-value { font-size: 16px; font-weight: 900; margin-top: 6px; }
        .kpi-value.primary { color: #2563eb; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px 8px; vertical-align: top; }
        th { background: #f1f5f9; text-align: left; font-weight: 900; font-size: 11px; color: #0f172a; }
        tbody tr:nth-child(even) td { background: #fafafa; }
        .right { text-align: right; }
        .title { font-weight: 900; font-size: 12px; margin-bottom: 8px; }
        .status { display: inline-block; padding: 4px 8px; border-radius: 999px; font-weight: 900; font-size: 10px; background: #e2e8f0; color: #0f172a; }
        .status.pending { background: #fef3c7; color: #92400e; }
        .status.paid { background: #dbeafe; color: #1d4ed8; }
        .status.processing { background: #e0f2fe; color: #0369a1; }
        .status.shipped { background: #e2e8f0; color: #334155; }
        .status.completed { background: #dcfce7; color: #166534; }
        .status.cancelled { background: #fee2e2; color: #991b1b; }
        .footer { position: fixed; left: 22px; right: 22px; bottom: 16px; border-top: 1px solid #e5e7eb; padding-top: 8px; font-size: 10px; color: #64748b; }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-table td { border: 0; padding: 0; }
    </style>
</head>
<body>
    @php
        $statusLabel = empty($statuses) ? 'SEMUA STATUS' : strtoupper(implode(', ', $statuses));
    @endphp

    <table style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="border:0; padding:0;">
                <div class="h1">REFRENS</div>
                <div class="muted" style="margin-top:2px;">Laporan Pesanan</div>
                <div class="muted small" style="margin-top:6px;">Periode: {{ $from }} s/d {{ $to }}</div>
                <div class="muted small" style="margin-top:2px;">Filter: {{ $statusLabel }}</div>
            </td>
            <td style="border:0; padding:0; text-align:right; vertical-align:top;">
                <span class="pill">EXPORT PDF</span>
                <div class="muted small" style="margin-top:10px;">Dibuat: {{ $generatedAt }}</div>
            </td>
        </tr>
    </table>

    <div class="spacer"></div>

    <table class="kpi-table">
        <tr>
            <td style="border:0; padding:0 8px 0 0;">
                <div class="kpi-box">
                    <div class="kpi-label">Total Pesanan</div>
                    <div class="kpi-value">{{ number_format($stats['total_orders'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border:0; padding:0 8px 0 8px;">
                <div class="kpi-box">
                    <div class="kpi-label">Total Pendapatan</div>
                    <div class="kpi-value primary">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border:0; padding:0 0 0 8px;">
                <div class="kpi-box">
                    <div class="kpi-label">Rata-rata / Order</div>
                    <div class="kpi-value">Rp {{ number_format($stats['avg_order_value'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="spacer"></div>

    <table style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:50%; border:0; padding:0 8px 0 0; vertical-align:top;">
                <div class="card">
                    <div class="title">Rekap Status</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="right">Jumlah</th>
                                <th class="right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byStatus as $row)
                                <tr>
                                    <td><span class="status {{ $row['status'] }}">{{ strtoupper($row['status']) }}</span></td>
                                    <td class="right">{{ number_format($row['count'], 0, ',', '.') }}</td>
                                    <td class="right">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
            <td style="width:50%; border:0; padding:0 0 0 8px; vertical-align:top;">
                <div class="card">
                    <div class="title">Top Produk</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="right">Qty</th>
                                <th class="right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td class="right">{{ number_format($row['qty'], 0, ',', '.') }}</td>
                                    <td class="right">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="spacer"></div>

    <div class="card">
        <div class="title">Daftar Order</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 42px;">No</th>
                    <th style="width: 120px;">Order</th>
                    <th>Pelanggan</th>
                    <th class="right" style="width: 120px;">Total</th>
                    <th style="width: 110px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $order)
                    <tr>
                        <td class="muted">{{ $idx + 1 }}</td>
                        <td>
                            <div style="font-weight:900;">#{{ $order->id }}</div>
                            <div class="muted small">{{ $order->created_at?->format('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            <div style="font-weight:800;">{{ $order->user?->name }}</div>
                            <div class="muted small">{{ $order->user?->email }}</div>
                        </td>
                        <td class="right" style="font-weight:900; color:#2563eb;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td><span class="status {{ $order->status }}">{{ strtoupper($order->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Tidak ada order.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>REFRENS • Laporan Pesanan</td>
                <td style="text-align:right;"><span class="page-number"></span></td>
            </tr>
        </table>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("DejaVu Sans", "normal");
                $pdf->text(515, 815, "Hal " . $PAGE_NUM . " / " . $PAGE_COUNT, $font, 9, array(100/255,116/255,139/255));
            ');
        }
    </script>
</body>
</html>
