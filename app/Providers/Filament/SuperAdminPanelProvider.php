<?php

namespace App\Providers\Filament;

use App\Filament\SuperAdmin\Widgets\ResumenWidget;
use App\Http\Middleware\EnsureSuperAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SuperAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('superadmin')
            ->path('superadmin')
            ->login()
            ->colors(['primary' => Color::hex('#6d28d9')])
            ->font('Inter', url: 'https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap')
            ->brandName('SchoolCore · Super Admin')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path('Filament/SuperAdmin/Resources'),
                for: 'App\Filament\SuperAdmin\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/SuperAdmin/Pages'),
                for: 'App\Filament\SuperAdmin\Pages'
            )
            ->pages([Dashboard::class])
            ->widgets([ResumenWidget::class])
            ->renderHook(
                'panels::styles.after',
                fn (): HtmlString => new HtmlString($this->getInlineCriticalCss())
            )
            ->renderHook(
                'panels::head.end',
                fn (): HtmlString => new HtmlString(
                    Blade::render('@vite(["resources/css/app.css"])') .
                    '<meta name="theme-color" content="#2e1065">'
                )
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureSuperAdmin::class,
            ]);
    }

    private function getInlineCriticalCss(): string
    {
        return <<<'CSS'
<style>
/* SchoolCore SuperAdmin — violet theme */
:root { --font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important; }

.fi-sidebar {
    background: linear-gradient(160deg, #2e1065 0%, #1a0940 100%) !important;
    border-right: none !important;
    box-shadow: 2px 0 20px rgba(0,0,0,0.3) !important;
}
.fi-sidebar-header { background: transparent !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.fi-sidebar-header .fi-logo,
.fi-sidebar-header .fi-logo *,
.fi-sidebar-header a,
.fi-sidebar-header a * { color: white !important; fill: white !important; }

.fi-sidebar-nav { padding: 1.25rem 0.5rem !important; }

.fi-sidebar-group-btn { padding: 0.875rem 0.75rem 0.25rem !important; display: flex !important; align-items: center !important; }
.fi-sidebar-group-label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.12em !important;
    color: rgba(255,255,255,0.3) !important;
    flex: 1 !important;
}

.fi-sidebar-item-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 10px !important;
    padding: 10px 14px !important;
    margin: 1px 6px !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: rgba(255,255,255,0.72) !important;
    text-decoration: none !important;
    background-color: transparent !important;
    transition: background-color 0.12s ease, color 0.12s ease !important;
}
.fi-sidebar-item-btn:hover {
    background-color: rgba(255,255,255,0.09) !important;
    color: rgba(255,255,255,0.95) !important;
}
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
    background-color: rgba(255,255,255,0.13) !important;
    color: white !important;
    border-left: 3px solid rgba(255,255,255,0.65) !important;
    padding-left: 11px !important;
}
.fi-sidebar-item-btn > .fi-icon,
.fi-sidebar-item-btn .fi-sidebar-item-icon { width: 18px !important; height: 18px !important; flex-shrink: 0 !important; color: rgba(255,255,255,0.45) !important; }
.fi-sidebar-item-btn:hover > .fi-icon,
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-icon { color: rgba(255,255,255,0.9) !important; }
.fi-sidebar-item-label { font-size: 14px !important; font-weight: 500 !important; color: inherit !important; flex: 1 !important; overflow: hidden !important; text-overflow: ellipsis !important; white-space: nowrap !important; }

.fi-sidebar-item-grouped-border,
.fi-sidebar-item-grouped-border-part,
.fi-sidebar-item-grouped-border-part-not-first,
.fi-sidebar-item-grouped-border-part-not-last { display: none !important; }
.fi-sidebar-sub-group-items .fi-sidebar-item-btn { padding-left: 36px !important; }

.fi-topbar nav, .fi-topbar { background-color: #ffffff !important; border-bottom: 1px solid #e8edf4 !important; box-shadow: 0 1px 6px rgba(0,0,0,0.06) !important; }
.fi-main, .fi-body { background-color: #f0f4f9 !important; }
.fi-header-heading { font-weight: 700 !important; font-size: 1.4rem !important; color: #0f172a !important; }
.fi-section { border-radius: 1rem !important; box-shadow: 0 1px 4px rgba(0,0,0,0.05) !important; }
.fi-simple-layout { background: #f0f4f9 !important; }
</style>
CSS;
    }
}
