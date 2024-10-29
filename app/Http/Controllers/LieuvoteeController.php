<?php

namespace App\Http\Controllers;

use App\Imports\LieuvoteeImport;
use App\Repositories\CentrevoteeeRepository;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\JuridictionRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LieuvoteeController extends Controller
{
    protected $lieuvoteeRepository;
    protected $centrevoteeeRepository;
    protected $regionRepository;
    protected $juridictionRepository;

    public function __construct(LieuvoteeRepository $lieuvoteeRepository, CentrevoteeRepository $centrevoteeeRepository,
    RegionRepository $regionRepository,JuridictionRepository $juridictionRepository){
        $this->lieuvoteeRepository =$lieuvoteeRepository;
        $this->centrevoteeeRepository = $centrevoteeeRepository;
        $this->regionRepository = $regionRepository;
        $this->juridictionRepository =$juridictionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lieuvotees = $this->lieuvoteeRepository->getAllLieuVotee();
        return view('lieuvotee.index',compact('lieuvotees'));
    }
    public function allLieuvoteApi(){
        $lieuvotes = $this->lieuvoteeRepository->getAllOnly();
        return response()->json($lieuvotes);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centrevotees = $this->centrevoteeeRepository->getAll();
        return view('lieuvotee.add',compact('centrevotees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $lieuvotes = $this->lieuvoteeRepository->store($request->all());
        return redirect('lieuvotee');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lieuvote = $this->lieuvoteeRepository->getById($id);
        return view('lieuvotee.show',compact('lieuvote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $centrevotees = $this->centrevoteeeRepository->getAll();
        $lieuvote = $this->lieuvoteeRepository->getById($id);
        return view('lieuvotee.edit',compact('lieuvote','centrevotees'));
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

        $this->lieuvoteeRepository->update($id, $request->all());
        return redirect('lieuvote');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->lieuvoteeRepository->destroy($id);
        return redirect('lieuvotee');
    }
    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'bail|required|file|mimes:xlsx'
        ]);
        Excel::import(new LieuvoteeImport,$request['file']);
        //  dd($data);
         return redirect()->back()->with('success', 'Données importées avec succès.');

      /*   // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
        $fichier = $request->file->move(public_path(), $request->file->hashName());

        // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
        $reader = SimpleExcelReader::create($fichier);

        // On récupère le contenu (les lignes) du fichier
        $rows = $reader->getRows();

        // $rows est une Illuminate\Support\LazyCollection

        // 4. On insère toutes les lignes dans la base de données
      //  $rows->toArray());
      $centrevotees = $this->centrevoteeeRepository->getAll();
      foreach ($rows as $key => $lieuvote) {
        foreach ($centrevotees as $key1 => $centrevotee) {
            if($lieuvote["centrevotee"]==$centrevotee->nom){
                Lieuvote::create([
                    "nom"=>$lieuvote['lieuvote'],
                    "centrevotee_id"=>$centrevotee->id,
                    "nb"=>$lieuvote['quantite'],
                ]);
            }
        }

    }
            // 5. On supprime le fichier uploadé
            $reader->close(); // On ferme le $reader
           // unlink($fichier);

            // 6. Retour vers le formulaire avec un message $msg
            return redirect()->back()->with('success', 'Données importées avec succès.'); */
    }
    public function getByCentrevotee($centrevotee){
        $lieuvotes = $this->lieuvoteeRepository->getByCentre($centrevotee);
        $nbBureau =  $this->lieuvoteeRepository->countByCentrevote($centrevotee);
        $electeurs = $this->lieuvoteeRepository->sumElecteurByCentrevote($centrevotee);
        $data=array("lieuvotes"=>$lieuvotes,"nbbureau"=>$nbBureau,
    "electeur"=>$electeurs);
        return response()->json($data);
    }

    public function carteElectoral(Request $request){
        $cartes = null;
        $national=0;
        $etarngers=1;
        $nbCentre = 0;
        $nbBureau = 0;
        $electeurs = 0;
        if($request->localite_id){
            $cartes = $this->lieuvoteeRepository->getByLocalite($request->localite_id);
            $nbCentre = $this->centrevoteeeRepository->countByLocalite($request->localite_id);

        }
        elseif($request->pays_id)
        {
            $cartes = $this->lieuvoteeRepository->getByPays($request->pays_id);
            $nbCentre = $this->centrevoteeeRepository->countByPays($request->pays_id);

        }
        elseif($request->juridiction_id)
        {
            $cartes = $this->lieuvoteeRepository->getByJuridiction($request->juridiction_id);
            $nbCentre = $this->centrevoteeeRepository->countByPays($request->pays_id);

        }
        $regions = $this->regionRepository->getRegionAsc();
        $juridictions = $this->juridictionRepository->getJuridictionAsc();
        $nbBureau =count($cartes);
        if(count($cartes) > 0){
            foreach ($cartes as $key => $value) {
                $electeurs = $value->electeurs + $electeurs;
            }
        }

        return view('carte',compact('cartes','regions','juridictions','national','etarngers',
        "nbCentre",'electeurs',"nbBureau"));


    }
    public function sumElecteurByLieudevote($lieuvote){
        $data = $this->lieuvoteeRepository->sumElecteurByLieudevote($lieuvote);
       
        return response()->json($data);
    }

    public function sommeByLieuvotee($id)
    {
        $lieuvote = $this->lieuvoteeRepository->getById($id);
        return response()->json($lieuvote);
    }
    public function sommeByPays($id)
    {
        $lieuvote = $this->lieuvoteeRepository->sumByPays($id);
        return response()->json($lieuvote);
    }
}
