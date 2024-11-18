<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
class RtsDepartemmentExport  implements FromCollection, WithHeadings
{
    use Exportable;

    private $rts;



    public function __construct($rts)
    {
      $this->rts = $rts;

    }

    public function collection()
    {
        return $this->rts;
    }

   /*  public function view(): View
    {
        $rts = $this->rts;
        $bullnull = $this->bullnull;
        $hs = $this->hs;
        $votant = $this->votant;
        $inscrit = $this->inscrit;
        $departement = $this->departement;
        return view('excel.rtsdepartement',['rts'=>$rts,'votant'=>$votant]);
    } */

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'Ont OBTENU',
            'Voix',
            '% des voix',
            'Inscrit',
            'Votant',
            'Nuls',
            'Exprimés',
            'Taux de participation ',
            'Siege'
        ];
    }
}
