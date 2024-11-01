<?php

namespace App\Http\Controllers;

use App\Imports\BureauTemoin;
use App\Imports\LieuvoteImport;
use App\Models\Lieuvote;
use App\Repositories\CentrevoteRepository;
use App\Repositories\LieuvoteRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;

class LieuvoteController extends Controller
{
    protected $lieuvoteRepository;
    protected $centrevoteRepository;

    public function __construct(LieuvoteRepository $lieuvoteRepository, CentrevoteRepository $centrevoteRepository){
        $this->lieuvoteRepository =$lieuvoteRepository;
        $this->centrevoteRepository = $centrevoteRepository;
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
}
