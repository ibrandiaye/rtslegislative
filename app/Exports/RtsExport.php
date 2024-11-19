<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RtsExport implements FromView
{
    protected $resultat;
    protected $candidats;

    public function __construct($resultat,$candidats)
    {
        $this->resultat  = $resultat;
        $this->candidats = $candidats  ;
    }
    public function view(): View
    {
        // Passer les données à la vue
        return view('excel.rtsdepartement', [
            'resultat' =>         $this->resultat,
            'candidats' =>         $this->candidats,
        ]);
    }
}
