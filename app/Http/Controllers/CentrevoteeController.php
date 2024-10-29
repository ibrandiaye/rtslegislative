<?php

namespace App\Http\Controllers;

use App\Models\Centrevotee;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\LocaliteRepository;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;

class CentrevoteeController extends Controller
{
    protected $centrevoteeRepository;
    protected $localiteRepository;
    protected $lieuvoteeRepository;

    public function __construct(CentrevoteeRepository $centrevoteeRepository, LocaliteRepository $localiteRepository,
    LieuvoteeRepository $lieuvoteeRepository){
        $this->centrevoteeRepository =$centrevoteeRepository;
        $this->localiteRepository = $localiteRepository;
        $this->lieuvoteeRepository =$lieuvoteeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $centrevotees = $this->centrevoteeRepository->getAllCentre();
        return view('centrevotee.index',compact('centrevotees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $localites = $this->localiteRepository->getAll();
        return view('centrevotee.add',compact('localites'));
    }
    public function allCentrevoteApi(){
        $centrevotes = $this->centrevoteeRepository->getAllOnly();
        return response()->json($centrevotes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $centrevotes = $this->centrevoteeRepository->store($request->all());
        return redirect('centrevotee');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $centrevote = $this->centrevoteeRepository->getById($id);
        return view('centrevotee.show',compact('centrevote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $localites = $this->localiteRepository->getAll();
        $centrevote = $this->centrevoteeRepository->getById($id);
        return view('centrevotee.edit',compact('centrevote','localites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->centrevoteeRepository->update($id, $request->all());
        return redirect('centrevotee');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->centrevoteeRepository->destroy($id);
        return redirect('centrevotee');
    }
    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'bail|required|file|mimes:xlsx'
        ]);

        // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
        $fichier = $request->file->move(public_path(), $request->file->hashName());

        // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
        $reader = SimpleExcelReader::create($fichier);

        // On récupère le contenu (les lignes) du fichier
        $rows = $reader->getRows();

        // $rows est une Illuminate\Support\LazyCollection

        // 4. On insère toutes les lignes dans la base de données
      //  $rows->toArray());
      $localites = $this->localiteRepository->getAll();
      foreach ($rows as $key => $centrevote) {
        foreach ($localites as $key1 => $localite) {
            if($centrevote["localite"]==$localite->nom){
                Centrevotee::create([
                    "nom"=>$centrevote['centrevote'],
                    "localite_id"=>$localite->id,

                ]);
            }
        }

    }
            // 5. On supprime le fichier uploadé
            $reader->close(); // On ferme le $reader
           // unlink($fichier);

            // 6. Retour vers le formulaire avec un message $msg
            return redirect()->back()->with('success', 'Données importées avec succès.');
    }
    public function getBylocalite($localite){
        $centrevotees = $this->centrevoteeRepository->getByLocalite($localite);
        $nbCentre =  $this->centrevoteeRepository->countByLocalite($localite);
        $nbBureau =  $this->lieuvoteeRepository->countByLocalite($localite);
        $electeurs = $this->lieuvoteeRepository->sumByLocalite($localite);
        $data=array("centrevotes"=>$centrevotees,"nbCentre"=>$nbCentre,"nbbureau"=>$nbBureau,
    "electeur"=>$electeurs);
        return response()->json($data);
    }
    public function sumElecteurByCentrevote($id){
        $electeurs = $this->lieuvoteeRepository->sumElecteurByCentrevote($id);
        return response()->json($electeurs);
    }

}
