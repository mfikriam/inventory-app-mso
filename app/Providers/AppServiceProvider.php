<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use FilamentVersions\Facades\FilamentVersions;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentVersions::registerNavigationView(false);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('filament.pages.profile')),
            ]);

            Filament::registerScripts([
                asset('plajin/lightbox2/js/lightbox-plus-jquery.js')
            ]);
            Filament::registerStyles([
                asset('plajin/lightbox2/css/lightbox.css')
            ]);

            Filament::registerRenderHook(
                name: 'scripts.start',
                callback: fn() => new HtmlString(html: "
                        <script>
                            document.addEventListener('DOMContentLoaded', function(){
                               setTimeout(() => {
                                    const activeSidebarItem = document.querySelector('.filament-sidebar-item-active');
                                    const sidebarWrapper = document.querySelector('.filament-sidebar-nav')

                                    sidebarWrapper.style.scrollBehavior = 'smooth';

                                    sidebarWrapper.scrollTo(0, activeSidebarItem.offsetTop - 250)
                               }, 300)
                            });
                        </script>
                "));

            Filament::registerTheme(
                app(Vite::class)('resources/css/app.css'),
            );

            Filament::registerScripts([
                'https://unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js',
            ], true);
        });

    }
}
