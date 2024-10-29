<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\JuridictionRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\PaysRepository;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;

class PaysController extends Controller
{
    protected $paysRepository;
    protected $juridictionRepository;
    protected $centrevoteeRepository;
    protected $lieuvoteeRepository;

    public function __construct(PaysRepository $paysRepository, JuridictionRepository $juridictionRepository,
    CentrevoteeRepository $centrevoteeRepository, LieuvoteeRepository $lieuvoteeRepository){
        $this->paysRepository =$paysRepository;
        $this->juridictionRepository = $juridictionRepository;
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
        $payss = $this->paysRepository->getAllWithJuridiction();
        return view('pays.index',compact('payss'));
    }

    public function allPaysApi(){
        $payss = $this->paysRepository->getAll();
        return response()->json($payss);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $juridictions = $this->juridictionRepository->getAll();
        return view('pays.add',compact('juridictions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payss = $this->paysRepository->store($request->all());
        return redirect('pays');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pays = $this->paysRepository->getById($id);
        return view('pays.show',compact('pays'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $juridictions = $this->juridictionRepository->getAll();
        $pays = $this->paysRepository->getById($id);
        return view('pays.edit',compact('pays','juridictions'));
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
        $this->paysRepository->update($id, $request->all());
        return redirect('pays');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->paysRepository->destroy($id);
        return redirect('pays');
    }
    public function    byJuridiction($juridiction){
        $payss = $this->paysRepository->getByJuridiction($juridiction);
        return response()->json($payss);
    }
    public function importExcel(Request $request)
    {
         /*  Excel::import(new PaysImport,$request['file']);
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
      $juridictions = $this->juridictionRepository->getAll();
      foreach ($rows as $key => $pays) {
        foreach ($juridictions as $key1 => $juridiction) {
            if($pays["juridiction"]==$juridiction->nom){
                Pays::create([
                    "nom"=>$pays['pays'],
                    "juridiction_id"=>$juridiction->id
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
    public function getByJuridiction($juridiction){
        $payss = $this->paysRepository->getByJuridiction($juridiction);
        $nbCentre =  $this->centrevoteeRepository->countJuridiction($juridiction);
        $nbBureau =  $this->lieuvoteeRepository->countByJuridiction($juridiction);
        $electeurs = $this->lieuvoteeRepository->sumByJuridiction($juridiction);
        $data=array("pays"=>$payss,"nbCentre"=>$nbCentre,"nbbureau"=>$nbBureau,
    "electeur"=>$electeurs);
        return response()->json($data);
    }
}
