<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Old format used calendar bimesters (ceil(month/2)):
        // Bim. 1/YEAR = Jan-Feb, Bim. 2 = Mar-Apr, Bim. 3 = May-Jun
        // Bim. 4/YEAR = Jul-Aug, Bim. 5 = Sep-Oct, Bim. 6 = Nov-Dec
        // New format uses Mexican school calendar month abbreviations.
        $map = [
            'Bim. 1/' => 'Ene-Feb ',
            'Bim. 2/' => 'Mar-Abr ',
            'Bim. 3/' => 'May-Jun ',
            'Bim. 4/' => 'Jul-Ago ',
            'Bim. 5/' => 'Sep-Oct ',
            'Bim. 6/' => 'Nov-Dic ',
        ];

        foreach ($map as $old => $new) {
            // Match "Bim. N/YEAR" → "Mon-Mon YEAR" (year stays the same)
            DB::table('colegiaturas')
                ->where('periodo', 'like', $old . '%')
                ->get(['id', 'periodo'])
                ->each(function ($row) use ($old, $new) {
                    $year = substr($row->periodo, strlen($old));
                    DB::table('colegiaturas')
                        ->where('id', $row->id)
                        ->update(['periodo' => $new . $year]);
                });
        }
    }

    public function down(): void
    {
        // Reverse mapping (new → old)
        $map = [
            'Ene-Feb ' => 'Bim. 1/',
            'Mar-Abr ' => 'Bim. 2/',
            'May-Jun ' => 'Bim. 3/',
            'Jul-Ago ' => 'Bim. 4/',
            'Sep-Oct ' => 'Bim. 5/',
            'Nov-Dic ' => 'Bim. 6/',
        ];

        foreach ($map as $old => $new) {
            DB::table('colegiaturas')
                ->where('periodo', 'like', $old . '%')
                ->get(['id', 'periodo'])
                ->each(function ($row) use ($old, $new) {
                    $year = substr($row->periodo, strlen($old));
                    DB::table('colegiaturas')
                        ->where('id', $row->id)
                        ->update(['periodo' => $new . $year]);
                });
        }
    }
};
