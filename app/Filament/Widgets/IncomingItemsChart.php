<?php
//
//namespace App\Filament\Widgets;
//
//use App\Models\IncomingItem;
//use Flowframe\Trend\Trend;
//use Flowframe\Trend\TrendValue;
//use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
//
//class IncomingItemsChart extends ApexChartWidget
//{
//    protected static string $chartId = 'incomingItemsChart';
//    protected static ?string $heading = 'Barang Masuk';
//    protected static ?string $pollingInterval = 'null';
//    protected static ?int $sort = 2;
//
//    protected function getOptions(): array
//    {
//        $data = Trend::model(IncomingItem::class)
//            ->dateColumn('date_entry')
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
//                    'name' => 'IncomingItemsChart',
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
