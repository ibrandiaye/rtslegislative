<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
class RtsDepartemmentExport  implements FromView
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.excelentreprise', [
            'data' => $this->data,
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
}
