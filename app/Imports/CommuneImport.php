<?php

namespace App\Imports;

use App\Models\Commune;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CommuneImport implements ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function array(array $data)
    {
        $departements=DB::table("departements")->get();
         foreach ($data as $key => $commune) {
            foreach ($departements as $key1 => $departement) {
                if($commune["departement"]==$departement->nom){
                    Commune::create([
                        "nom"=>$commune['commune'],
                        "departement_id"=>$departement->id,
                        "latitude"=>$commune['latitude'],
            "longitude"=>$commune['longitude']
                    ]);
                }
            }

        }
       // dd($data);
}
}
