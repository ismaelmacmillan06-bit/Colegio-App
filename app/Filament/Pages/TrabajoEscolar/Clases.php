<?php

namespace App\Filament\Pages\TrabajoEscolar;

use App\Models\Clase;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class Clases extends Page
{
    protected static ?string $navigationLabel = 'Clases';
    protected static ?int $navigationSort = 10;
    protected static ?string $slug = 'trabajo-escolar';
    protected string $view = 'filament.pages.trabajo-escolar.clases';
    protected static ?string $title = 'Clases';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Trabajo Escolar';
    }

    public static function getNavigationItems(): array
    {
        // Item principal: grid de todas las clases
        $items = [
            NavigationItem::make(static::getNavigationLabel() ?? 'Clases')
                ->icon(static::getNavigationIcon())
                ->url(static::getUrl())
                ->group(static::getNavigationGroup())
                ->sort(10)
                ->isActiveWhen(fn() => request()->routeIs('filament.admin.pages.trabajo-escolar')),
        ];

        // Sub-items: una por cada clase activa
        foreach (Clase::where('activo', true)->orderBy('nombre')->get() as $index => $clase) {
            $items[] = NavigationItem::make($clase->nombre)
                ->icon('heroicon-o-user-group')
                ->url('/admin/trabajo-escolar/clase?claseId=' . $clase->id)
                ->group(static::getNavigationGroup())
                ->sort(11 + $index)
                ->isActiveWhen(fn() =>
                    request()->routeIs('filament.admin.pages.trabajo-escolar.clase') &&
                    request()->query('claseId') == $clase->id
                );
        }

        return $items;
    }

    #[Computed]
    public function clases()
    {
        return Clase::withCount(['alumnos', 'docentes'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }
}
