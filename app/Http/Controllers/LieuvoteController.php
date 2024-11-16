<?php

namespace App\Http\Controllers;

use App\Imports\BureauTemoin;
use App\Imports\LieuvoteImport;
use App\Models\Lieuvote;
use App\Repositories\ArrondissementRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;

class LieuvoteController extends Controller
{
    protected $lieuvoteRepository;
    protected $centrevoteRepository;
    protected $communeRepository;
    protected $arrondissementRepository;
    protected $departementRepository;
    protected $regionRepository;

    public function __construct(LieuvoteRepository $lieuvoteRepository, CentrevoteRepository $centrevoteRepository,
    CommuneRepository $communeRepository,ArrondissementRepository $arrondissementRepository,DepartementRepository $departementRepository,
    RegionRepository $regionRepository){
        $this->lieuvoteRepository =$lieuvoteRepository;
        $this->centrevoteRepository = $centrevoteRepository;
        $this->communeRepository = $communeRepository;
        $this->arrondissementRepository = $arrondissementRepository;
        $this->departementRepository = $departementRepository;
        $this->regionRepository = $regionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lieuvotes = $this->lieuvoteRepository->getAllWithLocalite();
        return view('lieuvote.index',compact('lieuvotes'));
    }
    public function allLieuvoteApi(){
        $lieuvotes = $this->lieuvoteRepository->getAllOnly();
        return response()->json($lieuvotes);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centrevotes = $this->centrevoteRepository->getAll();
        return view('lieuvote.add',compact('centrevotes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $lieuvotes = $this->lieuvoteRepository->store($request->all());
        return redirect('lieuvote');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lieuvote = $this->lieuvoteRepository->getById($id);
        return view('lieuvote.show',compact('lieuvote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $centrevotes = $this->centrevoteRepository->getAll();
        $lieuvote = $this->lieuvoteRepository->getById($id);
        return view('lieuvote.edit',compact('lieuvote','centrevotes'));
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

        $this->lieuvoteRepository->update($id, $request->all());
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
        $this->lieuvoteRepository->destroy($id);
        return redirect('lieuvote');
    }
    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'bail|required|file|mimes:xlsx'
        ]);
        Excel::import(new LieuvoteImport,$request['file']);
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
      $centrevotes = $this->centrevoteRepository->getAll();
      foreach ($rows as $key => $lieuvote) {
        foreach ($centrevotes as $key1 => $centrevote) {
            if($lieuvote["centrevote"]==$centrevote->nom){
                Lieuvote::create([
                    "nom"=>$lieuvote['lieuvote'],
                    "centrevote_id"=>$centrevote->id,
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
    public function getByCentreVote($centrevote){
        $lieuvotes = $this->lieuvoteRepository->getByCentre($centrevote);
        return response()->json($lieuvotes);
    }
    public function getTemoinByCentreVote($centrevote){
        $lieuvotes = $this->lieuvoteRepository->getByLieuvoteTemoin($centrevote);
        return response()->json($lieuvotes);
    }
    public function getByLieuvoteTemoinParticipation($centrevote){
        $lieuvotes = $this->lieuvoteRepository->getByLieuvoteTemoinParticipation($centrevote);
        return response()->json($lieuvotes);
    }

    

    public function getById($id)
    {
        $lieuvote = $this->lieuvoteRepository->getById($id);
        return response()->json($lieuvote);
    }


    public function importTemoin(Request $request)
    {
        $this->validate($request, [
            'file' => 'bail|required|file|mimes:xlsx'
        ]);
        Excel::import(new BureauTemoin,$request['file']);
        //  dd($data);
         return redirect()->back()->with('success', 'Données importées avec succès.');

    }
    public function sumElecteurByCommune($id){
        $electeurs = $this->lieuvoteRepository->sommeByCommune($id);
        return response()->json($electeurs);
    }
    public function sumElecteurByDepartement($id){
        $electeurs = $this->lieuvoteRepository->sommeByDepartement($id);
        return response()->json($electeurs);
    }
    public function getByCentrevotePrefer($id)
    {
        $lieuvotes = $this->lieuvoteRepository->getByCentreVote($id);   
        return view("bureau.bureauvote",compact("lieuvotes"));
    }

    public function getByDepartement()
    {
        $user = Auth::user();
        $commune_id             = "";
        $centrevote_id          = "";
        $lieuvote_id            = "";
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        $arrondissement_id      = "";
        $arrondissements = $this->arrondissementRepository->getByDepartement($user->departement_id);

        $lieuvotess = $this->lieuvoteRepository->getByDepartement(Auth::user()->departement_id);  
        return view("lieuvote.bydepartement",compact("arrondissements","arrondissement_id","commune_id","centrevote_id",
        "lieuvote_id","communes","centreVotes","lieuVotes","lieuvotess"));
 
    }

    public function getAllAndEtat()
    {
        $user = Auth::user();
        $commune_id             = "";
        $centrevote_id          = "";
        $lieuvote_id            = "";
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        $arrondissement_id      = "";
        $arrondissements =[];
        $departement_id      = "";
        $departements =[];
        $region_id      = "";
        $regions = $this->regionRepository->getAllOnLy();
        $etat = null;
        $temoin = null;
        $lieuvotess =[];//$this->lieuvoteRepository->getByDepartement(Auth::user()->departement_id);  
        return view("lieuvote.bynational",compact("arrondissements","arrondissement_id","commune_id","centrevote_id",
        "lieuvote_id","communes","centreVotes","lieuVotes","lieuvotess","departement_id","departements","region_id","regions","etat","temoin"));
 
    }

    public function search(Request $request)
    {
        $req = $this->lieuvoteRepository->search();
        $user = Auth::user();
        $commune_id             = $request->commune_id;
        $centrevote_id          = $request->centrevote_id;
        $lieuvote_id            = $request->lieuvote_id;
        $arrondissement_id      = $request->arrondissement_id;
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
       
        $arrondissements = $this->arrondissementRepository->getByDepartement($user->departement_id);
        if($request->arrondissement_id)
        {
            $communes = $this->communeRepository->getByArrondissement($arrondissement_id);
            
            $req = $req->where("communes.arrondissement_id",$request->arrondissement_id);
        }
        if($request->commune_id)
        {
            $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
               
            $req = $req->where("communes.id",$request->commune_id);
        }
        if($request->centrevote_id)
        {
            $lieuVotes = $this->lieuvoteRepository->getByCentreVote($centrevote_id);
            $req = $req->where("centrevotes.id",$request->centrevote_id);
        }
        if($request->lieuvote_id)
        {
          
            $req = $req->where("lieuvotes.id",$request->lieuvote_id);
        }
        $lieuvotess = $req->get();
        return view("lieuvote.bydepartement",compact("arrondissements","arrondissement_id","commune_id","centrevote_id",
        "lieuvote_id","communes","centreVotes","lieuVotes","lieuvotess"));

    }

    public function searchNational(Request $request)
    {
        $req = $this->lieuvoteRepository->searchNational();
        $user = Auth::user();
        $commune_id             = $request->commune_id;
        $centrevote_id          = $request->centrevote_id;
        $lieuvote_id            = $request->lieuvote_id;
        $arrondissement_id      = $request->arrondissement_id;
        $departement_id         = $request->departement_id;
        $temoin                 = $request->temoin;
        $region_id              = $request->region_id;
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        $departements =[];
        $etat      = $request->etat;
        $regions = $this->regionRepository->getAllOnLy();
       
        $arrondissements =[];

        //dd($region_id);
        if($request->region_id)
        {
            $departements = $this->departementRepository->getByRegion($region_id);
            
            $req = $req->where("departements.region_id",$request->region_id);
        }
        if($request->departement_id)
        {
            $arrondissements = $this->arrondissementRepository->getByDepartement($departement_id);
               
            $req = $req->where("communes.departement_id",$request->departement_id);
        }
        if($request->arrondissement_id)
        {
            $communes = $this->communeRepository->getByArrondissement($arrondissement_id);
            
            $req = $req->where("communes.arrondissement_id",$request->arrondissement_id);
        }
        if($request->commune_id)
        {
            $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
               
            $req = $req->where("communes.id",$request->commune_id);
        }
        if($request->centrevote_id)
        {
            $lieuVotes = $this->lieuvoteRepository->getByCentreVote($centrevote_id);
            $req = $req->where("centrevotes.id",$request->centrevote_id);
        }
        if($request->lieuvote_id)
        {
          
            $req = $req->where("lieuvotes.id",$request->lieuvote_id);
        }
        if($request->etat)
        {
          
            $req = $req->where("lieuvotes.etat",$request->etat);
        }
        if($request->temoin)
        {
          
            $req = $req->where("lieuvotes.temoin",$request->temoin);
        }
        $lieuvotess = $req->get();
        return view("lieuvote.bynational",compact("arrondissements","arrondissement_id","commune_id","centrevote_id",
        "lieuvote_id","communes","centreVotes","lieuVotes","lieuvotess","departements","departement_id","region_id","regions","etat","temoin"));

    }

    public function mettreBureauTemoin($id)
    {
        $this->lieuvoteRepository->mettreBureauTemoin($id);
        return redirect("bureau/by/national")->with('success', 'Opération avec succés.');
    }
    public function enleverBureauTemoin($id)
    {
        $this->lieuvoteRepository->enleverBureauTemoin($id);
        return redirect("bureau/by/national")->with('success', 'Opération avec succés.');
    }
}
