<?php

namespace App\Models;

use App\Models\Scopes\ColegioScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(ColegioScope::class)]
class Colegiatura extends Model
{
    protected $table = 'colegiaturas';

    protected $fillable = [
        'alumno_id', 'colegio_id', 'nivel_colegiatura_id',
        'periodo', 'monto', 'monto_original', 'descuento_pct', 'tipo_cobro',
        'status', 'fecha_vencimiento', 'fecha_pago', 'notas',
    ];

    protected $casts = [
        'monto'          => 'decimal:2',
        'monto_original' => 'decimal:2',
        'descuento_pct'  => 'integer',
        'fecha_vencimiento' => 'date',
        'fecha_pago'        => 'date',
    ];

    public function alumno()      { return $this->belongsTo(Alumno::class); }
    public function colegio()     { return $this->belongsTo(Colegio::class); }
    public function nivelConfig() { return $this->belongsTo(NivelColegiatura::class, 'nivel_colegiatura_id'); }

    // ─────────────────────────────────────────────────────────────────
    // Mexican school calendar bimesters:
    //   Sep-Oct · Nov-Dic · Ene-Feb · Mar-Abr · May-Jun
    // School year: Sep {Y} – Jun {Y+1}
    // ─────────────────────────────────────────────────────────────────

    public static function generarPeriodo(string $tipoCobro, ?Carbon $fecha = null): string
    {
        $fecha ??= Carbon::now();
        $year  = $fecha->year;
        $month = $fecha->month;

        if ($tipoCobro === 'Bimestral') {
            return match (true) {
                $month === 9  || $month === 10 => "Sep-Oct $year",
                $month === 11 || $month === 12 => "Nov-Dic $year",
                $month === 1  || $month === 2  => "Ene-Feb $year",
                $month === 3  || $month === 4  => "Mar-Abr $year",
                $month === 5  || $month === 6  => "May-Jun $year",
                default                        => "Sep-Oct $year", // Jul-Ago = vacaciones
            };
        }

        return match ($tipoCobro) {
            'Semestral' => 'Sem. ' . ($month <= 6 ? '1' : '2') . '/' . $year,
            default     => $fecha->translatedFormat('F Y'),
        };
    }

    public static function calcularVencimiento(string $tipoCobro, ?Carbon $fecha = null): Carbon
    {
        $fecha ??= Carbon::now();
        $year  = $fecha->year;
        $month = $fecha->month;

        if ($tipoCobro === 'Bimestral') {
            return match (true) {
                $month === 9  || $month === 10 => Carbon::create($year, 10)->endOfMonth(),
                $month === 11 || $month === 12 => Carbon::create($year, 12)->endOfMonth(),
                $month === 1  || $month === 2  => Carbon::create($year, 2)->endOfMonth(),
                $month === 3  || $month === 4  => Carbon::create($year, 4)->endOfMonth(),
                $month === 5  || $month === 6  => Carbon::create($year, 6)->endOfMonth(),
                default                        => Carbon::create($year, 10)->endOfMonth(),
            };
        }

        return match ($tipoCobro) {
            'Semestral' => $month <= 6
                ? Carbon::create($year, 6)->endOfMonth()
                : Carbon::create($year, 12)->endOfMonth(),
            default => $fecha->copy()->endOfMonth(),
        };
    }

    /**
     * Parse "Sep-Oct 2025" → end-of-period Carbon date (last day of the second month).
     */
    public static function calcularVencimientoPeriodo(string $periodo): Carbon
    {
        $meses = [
            'Ene' => 1, 'Feb' => 2, 'Mar' => 3, 'Abr' => 4,
            'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Ago' => 8,
            'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dic' => 12,
        ];

        preg_match('/^(\w{3})-(\w{3})\s+(\d{4})$/', trim($periodo), $m);
        $endMonth = isset($m[2]) ? ($meses[$m[2]] ?? 12) : 12;
        $year     = isset($m[3]) ? (int) $m[3] : (int) date('Y');

        return Carbon::create($year, $endMonth)->endOfMonth();
    }

    /**
     * Returns the 5 bimestral period labels for a school year.
     */
    public static function getSchoolYearPeriods(?int $startYear = null): array
    {
        if ($startYear === null) {
            $startYear = self::getCurrentSchoolYear();
        }
        $endYear = $startYear + 1;

        return [
            "Sep-Oct $startYear",
            "Nov-Dic $startYear",
            "Ene-Feb $endYear",
            "Mar-Abr $endYear",
            "May-Jun $endYear",
        ];
    }

    /**
     * Starting year of the current school year.
     * Sep-Dec → current year.  Jan-Aug → previous year.
     */
    public static function getCurrentSchoolYear(): int
    {
        $month = Carbon::now()->month;
        return $month >= 9 ? Carbon::now()->year : Carbon::now()->year - 1;
    }
}
