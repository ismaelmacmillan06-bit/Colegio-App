<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use App\Models\Slider;
use App\Models\Galeria;
use App\Models\MenuCafeteria;
use App\Models\CuadroHonor;

class PublicController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('activo', true)->orderBy('orden')->get();
        $circulares = Circular::where('activo', true)->orderBy('fecha', 'desc')->take(4)->get();
        $cuadroHonor = CuadroHonor::where('activo', true)->orderBy('orden')->take(6)->get();
        $galeria = Galeria::where('activo', true)->orderBy('orden')->take(8)->get();

        return view('public.index', compact('sliders', 'circulares', 'cuadroHonor', 'galeria'));
    }

    public function circulares()
    {
        $circulares = Circular::where('activo', true)->orderBy('fecha', 'desc')->paginate(12);
        return view('public.circulares', compact('circulares'));
    }

    public function galeria()
    {
        $galeria = Galeria::where('activo', true)->orderBy('orden')->paginate(16);
        return view('public.galeria', compact('galeria'));
    }

    public function menu()
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $menu = MenuCafeteria::where('activo', true)
            ->orderByRaw("FIELD(dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes')")
            ->get()
            ->keyBy('dia');

        return view('public.menu', compact('menu', 'dias'));
    }
}