<?php

namespace App\Http\Controllers;

use App\Models\Localite;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\LocaliteRepository;
use App\Repositories\PaysRepository;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;

class LocaliteController extends Controller
{
    protected $localiteRepository;
    protected $paysRepository;

    protected $centrevoteeRepository;
    protected $lieuvoteeRepository;
    public function __construct(LocaliteRepository $localiteRepository, PaysRepository $paysRepository,
    CentrevoteeRepository $centrevoteeRepository, LieuvoteeRepository $lieuvoteeRepository){
        $this->localiteRepository =$localiteRepository;
        $this->paysRepository = $paysRepository;
        $this->centrevoteeRepository =$centrevoteeRepository;
        $this->lieuvoteeRepository =$lieuvoteeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localites = $this->localiteRepository->getAllWithpays();
        return view('localite.index',compact('localites'));
    }

    public function allLocaliteApi()
    {
        $localites = $this->localiteRepository->getAllOnLy();
        return response()->json($localites);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payss = $this->paysRepository->getAll();
        return view('localite.add',compact('payss'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $localites = $this->localiteRepository->store($request->all());
        return redirect('localite');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $localite = $this->localiteRepository->getById($id);
        return view('localite.show',compact('localite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payss = $this->paysRepository->getAll();
        $localite = $this->localiteRepository->getById($id);
        return view('localite.edit',compact('localite','payss'));
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

        $this->localiteRepository->update($id, $request->all());
        return redirect('localite');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->localiteRepository->destroy($id);
        return redirect('localite');
    }
    public function byPays($pays){
        $localites = $this->localiteRepository->getByPays($pays);
        $nbCentre =  $this->centrevoteeRepository->countByPays($pays);
        $nbBureau =  $this->lieuvoteeRepository->countByPays($pays);
        $electeurs = $this->lieuvoteeRepository->sumByPays($pays);
        $data=array("localites"=>$localites,"nbCentre"=>$nbCentre,"nbbureau"=>$nbBureau,
    "electeur"=>$electeurs);
        return response()->json($data);
    }
    public function importExcel(Request $request)
    {
         /*  Excel::import(new LocaliteImport,$request['file']);
       //  dd($data);
        return redirect()->back()->with('success', 'Données importées avec succès.'); */

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
      $payss = $this->paysRepository->getAll();
      foreach ($rows as $key => $localite) {
        foreach ($payss as $key1 => $pays) {
            if($localite["pays"]==$pays->nom){
                Localite::create([
                    "nom"=>$localite['localite'],
                    "pays_id"=>$pays->id
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

    public function getByPays($pays){
        $localites = $this->localiteRepository->getByPays($pays);
        $nbCentre =  $this->centrevoteeRepository->countByPays($pays);
        $nbBureau =  $this->lieuvoteeRepository->countByPays($pays);
        $electeurs = $this->lieuvoteeRepository->sumByPays($pays);
        $data=array("localites"=>$localites,"nbCentre"=>$nbCentre,"nbbureau"=>$nbBureau,
    "electeur"=>$electeurs);
        return response()->json($data);
    }
}
