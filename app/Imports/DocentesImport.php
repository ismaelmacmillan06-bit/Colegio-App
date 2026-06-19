<?php

namespace App\Imports;

use App\Models\Docente;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DocentesImport
{
    public array $errores = [];
    public int $importados = 0;

    public function importar(string $rutaArchivo): void
    {
        $spreadsheet = IOFactory::load($rutaArchivo);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        array_shift($rows); // quitar encabezado

        $colegioId = auth()->user()?->colegio_id;

        $materiasIndex = Materia::where('activo', true)
            ->get()
            ->keyBy(fn($m) => mb_strtolower(trim($m->nombre)));

        foreach ($rows as $fila) {
            if (empty(trim((string) ($fila[0] ?? '')))) continue;

            [$nombre, $apellidos, $tipo, $telefono, $materiasTexto, $email, $password] = array_pad($fila, 7, null);

            $tipoValido = in_array($tipo, ['titular', 'especialista', 'extracurricular', 'directivo'])
                ? $tipo
                : 'titular';

            // Crear cuenta de usuario si se proporcionó email
            $userId = null;
            $emailLimpio = trim((string) ($email ?? ''));
            if ($emailLimpio) {
                if (User::where('email', $emailLimpio)->exists()) {
                    $this->errores[] = "Email '{$emailLimpio}' ya existe (docente: {$nombre} {$apellidos})";
                } else {
                    $passwordLimpio = trim((string) ($password ?? ''));
                    $user = User::create([
                        'name'       => trim((string) $nombre) . ' ' . trim((string) $apellidos),
                        'email'      => $emailLimpio,
                        'password'   => Hash::make($passwordLimpio ?: str()->random(16)),
                        'colegio_id' => $colegioId,
                    ]);
                    $userId = $user->id;
                }
            }

            $docente = Docente::create([
                'colegio_id' => $colegioId,
                'user_id'    => $userId,
                'nombre'     => trim((string) $nombre),
                'apellidos'  => trim((string) $apellidos),
                'tipo'       => $tipoValido,
                'telefono'   => trim((string) ($telefono ?? '')),
                'activo'     => true,
            ]);

            if ($materiasTexto) {
                $nombresMateria = array_map('trim', explode(',', (string) $materiasTexto));
                $materiasIds    = [];

                foreach ($nombresMateria as $nombreM) {
                    $key = mb_strtolower($nombreM);
                    if (isset($materiasIndex[$key])) {
                        $materiasIds[] = $materiasIndex[$key]->id;
                    } else {
                        $this->errores[] = "Materia '{$nombreM}' no encontrada para {$nombre} {$apellidos}";
                    }
                }

                if ($materiasIds) {
                    $docente->materias()->sync($materiasIds);
                }
            }

            $this->importados++;
        }
    }
}
