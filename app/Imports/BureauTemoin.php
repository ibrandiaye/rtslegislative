<?php

namespace App\Imports;

use App\Models\Lieuvote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BureauTemoin implements  ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function array(array $data)
    {
        $lieuvotes=DB::table("lieuvotes")
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->join("communes","centrevotes.commune_id","=","communes.id")
        ->select("communes.nom as commune","centrevotes.nom as centre","lieuvotes.id","lieuvotes.nom")
        ->get();
        
        $nontrouve =array();
        foreach ($data as $key => $lieuvote) {
            $verif =false;
           foreach ($lieuvotes as $key1 => $lieuvotebd) {
               if($lieuvote["centrevote"]==$lieuvotebd->centre && $lieuvote["commune"]==$lieuvotebd->commune && $lieuvote["bureau"]==$lieuvotebd->nom){
                   Lieuvote::where("id",$lieuvotebd->id)->update(["temoin"=>true]);
                   $verif = true;
               }
           }
           if(!$verif){
            $nontrouve[] =  "commune : " . $lieuvote["commune"]. " Centre: ".$lieuvote["centrevote"]. " " ." Bureau : ". " ".$lieuvote["bureau"];
            }
           

       }
     //  dd($nontrouve);
    }
}
