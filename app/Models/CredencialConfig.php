<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CredencialConfig extends Model
{
    protected $table = 'credencial_config';

    protected $fillable = [
        'nombre_escuela',
        'nombre_director',
        'cargo_director',
        'domicilio',
        'telefono',
        'email',
        'web',
        'terminos',
        'color_primario',
        'logo_path',
        'firma_path',
        'frente_path',
        'reverso_path',
    ];

    public static function obtener(): self
    {
        return self::firstOrCreate([], [
            'nombre_escuela'  => 'Centro Educativo',
            'nombre_director' => 'Director General',
            'cargo_director'  => 'Director General',
            'color_primario'  => '#c0392b',
        ]);
    }

    public function logoBase64(): ?string   { return $this->imagenBase64($this->logo_path); }
    public function firmaBase64(): ?string  { return $this->imagenBase64($this->firma_path); }
    public function frenteBase64(): ?string { return $this->imagenBase64($this->frente_path); }
    public function reversoBase64(): ?string{ return $this->imagenBase64($this->reverso_path); }

    private function imagenBase64(?string $path): ?string
    {
        if (! $path) return null;
        $fullPath = storage_path('app/public/' . $path);
        if (! file_exists($fullPath)) return null;
        $mime = mime_content_type($fullPath);
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
    }
}
