<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\CuadroHonor;
use App\Models\Galeria;
use App\Models\MenuCafeteria;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;

class WebPublicaController extends Controller
{
    public function sliders(): JsonResponse
    {
        $sliders = Slider::where('activo', true)
            ->orderBy('orden')
            ->get()
            ->map(fn ($s) => [
                'id'         => $s->id,
                'titulo'     => $s->titulo,
                'subtitulo'  => $s->subtitulo,
                'imagen_url' => $s->imagen ? asset('storage/' . $s->imagen) : null,
            ]);

        return response()->json($sliders);
    }

    public function menu(): JsonResponse
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        $menu = MenuCafeteria::where('activo', true)
            ->orderByRaw("FIELD(dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes')")
            ->get(['dia', 'platillo_principal', 'sopa', 'bebida', 'fruta', 'precio']);

        return response()->json([
            'dias'  => $dias,
            'menu'  => $menu,
        ]);
    }

    public function circulares(): JsonResponse
    {
        $circulares = Circular::where('activo', true)
            ->orderBy('fecha', 'desc')
            ->paginate(12)
            ->through(fn ($c) => [
                'id'               => $c->id,
                'titulo'           => $c->titulo,
                'descripcion'      => $c->descripcion,
                'fecha'            => $c->fecha?->format('Y-m-d'),
                'fecha_formateada' => $c->fecha?->format('d/m/Y'),
                'pdf_url'          => $c->archivo_pdf ? asset('storage/' . $c->archivo_pdf) : null,
            ]);

        return response()->json($circulares);
    }

    public function galeria(): JsonResponse
    {
        $galeria = Galeria::where('activo', true)
            ->orderBy('orden')
            ->paginate(24)
            ->through(fn ($g) => [
                'id'          => $g->id,
                'titulo'      => $g->titulo,
                'descripcion' => $g->descripcion,
                'imagen_url'  => $g->imagen ? asset('storage/' . $g->imagen) : null,
            ]);

        return response()->json($galeria);
    }

    public function cuadroHonor(): JsonResponse
    {
        $cuadro = CuadroHonor::where('activo', true)
            ->orderBy('orden')
            ->get()
            ->map(fn ($c) => [
                'nombre_alumno' => $c->nombre_alumno,
                'grado'         => $c->grado,
                'grupo'         => $c->grupo,
                'periodo'       => $c->periodo,
                'motivo'        => $c->motivo,
                'foto_url'      => $c->foto ? asset('storage/' . $c->foto) : null,
            ]);

        return response()->json($cuadro);
    }
}
