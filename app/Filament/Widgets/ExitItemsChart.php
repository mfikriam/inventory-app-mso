<?php
//
//namespace App\Filament\Widgets;
//
//use App\Models\ExitItem;
//use Flowframe\Trend\Trend;
//use Flowframe\Trend\TrendValue;
//use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
//
//class ExitItemsChart extends ApexChartWidget
//{
//    protected static string $chartId = 'exitItemsChart';
//    protected static ?string $heading = 'Barang Keluar';
//    protected static ?string $pollingInterval = 'null';
//
//    protected static ?int $sort = 3;
//
//    protected function getOptions(): array
//    {
//        $data = Trend::model(ExitItem::class)
//            ->dateColumn('date_out_date')
//            ->between(
//                start: now()->startOfYear(),
//                end: now()->endOfYear(),
//            )->perMonth()->count();
//
//        return [
//            'chart' => [
//                'type' => 'line',
//                'height' => 200,
//            ],
//            'series' => [
//                [
//                    'name' => 'Barang Keluar',
//                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
//                ],
//            ],
//            'xaxis' => [
//                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
//                'labels' => [
//                    'style' => [
//                        'colors' => 'rgb(142, 209, 76)',
//                        'fontWeight' => 600,
//                    ],
//                ],
//            ],
//            'yaxis' => [
//                'labels' => [
//                    'style' => [
//                        'colors' => '#9ca3af',
//                        'fontWeight' => 600,
//                    ],
//                ],
//            ],
//            'colors' => ['#0EA5E9'],
//            'stroke' => [
//                'curve' => 'smooth',
//            ],
//        ];
//    }
//}
