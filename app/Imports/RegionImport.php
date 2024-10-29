<?php

namespace App\Imports;

use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MongoDB\Driver\Session;
use MongoDB\Driver\Manager;

class RegionImport implements  ToArray, WithHeadingRow,WithChunkReading
{
    /**
    * @param ToArray $array
    */
    public function array(array $data)
    {
$manager = new Manager('mongodb://localhost:27017');
$session = $manager::startSession() ;

try {
    $session->startTransaction();

    // Effectuez des opérations MongoDB dans la transaction
    // Par exemple, utilisez le modèle Eloquent pour interagir avec MongoDB.

 foreach ($data as $key => $region) {
         Region::create([
            "nom"=>$region['nom'],
            "latitude"=>$region['latitude'],
            "longitude"=>$region['longitude']
        ]);
      }
    // Si tout se passe bien, confirmez la transaction
    $session->commitTransaction();
} catch (\Exception $e) {
    // En cas d'erreur, annulez la transaction
    $session->abortTransaction();
    // Gérez l'erreur
} finally {
    $session->endSession();
}

       /* try {

         $session->startTransaction();
         foreach ($data as $key => $region) {
         Region::create([
            "nom"=>$region['nom'],
            "latitude"=>$region['latitude'],
            "longitude"=>$region['longitude']
        ]);

  // transaction logic


        $regionSave = new Region;
        $regionSave->nom= $region['nom'];
        $regionSave->save();
        }
       // dd($data);
       $transaction->commit();
} catch (\Exception $e) {
  $transaction->abort();
} */
}
public function chunkSize():int{
  return 1;
}
}
