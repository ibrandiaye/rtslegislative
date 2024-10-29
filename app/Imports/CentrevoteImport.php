<?php

namespace App\Imports;

use App\Models\Centrevote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CentrevoteImport implements ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function array(array $data)
    {
        $communes=DB::table("communes")->get();
         foreach ($data as $key => $centrevote) {
            foreach ($communes as $key1 => $commune) {
                if($centrevote["commune"]==$commune->nom){
                    Centrevote::create([
                        "nom"=>$centrevote['centrevote'],
                        "commune_id"=>$commune->id,

                    ]);
                }
            }

        }
       // dd($data);
}
}
