<?php

namespace App\Imports;

use App\Models\Departement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class DepartementImport implements ToArray, WithHeadingRow
{
    public function array(array $data)
    {
        $regions=DB::table("regions")->get();
         foreach ($data as $key => $departement) {
            foreach ($regions as $key1 => $region) {
                if($departement["region"]==$region->nom){
                    Departement::create([
                        "nom"=>$departement['departement'],
                        "region_id"=>$region->id,
                        "latitude"=>$departement['latitude'],
                         "longitude"=>$departement['longitude']
                    ]);
                }
            }

        }
       // dd($data);
}
}
