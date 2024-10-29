<?php

namespace App\Imports;

use App\Models\Centrevotee;
use App\Models\Lieuvotee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LieuvoteeImport implements ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function array(array $data)
    {
        $centrevotes=Centrevotee::with("localite")->get();
        foreach ($data as $key => $lieuvote) {
           foreach ($centrevotes as $key1 => $centrevote) {
               if($lieuvote["centrevote"]==$centrevote->nom && $lieuvote["localite"]==$centrevote->localite->nom){
                   Lieuvotee::create([
                       "nom"=>$lieuvote['lieuvote'],
                       "centrevotee_id"=>$centrevote->id,
                       "nb"=>$lieuvote['quantite'],
                   ]);
               }
           }

       }
    }
}

