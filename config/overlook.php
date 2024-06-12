<?php

return [
    'includes' => [
        \App\Filament\Resources\ExitItemResource::class,
        \App\Filament\Resources\IncomingItemResource::class,
    ],
    'excludes' => [
        // App\Filament\Resources\Blog\AuthorResource::class,
    ],
    'should_convert_count' => true,
    'enable_convert_tooltip' => true,
    'grid' => [
        'default' => 2,
        'sm' => 2,
        'md' => 2,
        '2xl' => null,
    ],
    'disable_css' => false,
    'disable_sorting' => false,
];
