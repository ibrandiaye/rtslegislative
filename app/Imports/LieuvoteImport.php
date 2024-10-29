<?php

namespace App\Imports;

use App\Models\Centrevote;
use App\Models\Lieuvote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LieuvoteImport implements ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function array(array $data)
    {
        $centrevotes=Centrevote::with("commune")->get();
        foreach ($data as $key => $lieuvote) {
           foreach ($centrevotes as $key1 => $centrevote) {
               if($lieuvote["centrevote"]==$centrevote->nom && $lieuvote["commune"]==$centrevote->commune->nom){
                   Lieuvote::create([
                       "nom"=>$lieuvote['lieuvote'],
                       "centrevote_id"=>$centrevote->id,
                       "nb"=>$lieuvote['quantite']

                   ]);
               }
           }

       }
    }
}
