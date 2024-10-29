<?php

namespace App\Http\Controllers;

use App\Models\Juridiction;
use App\Repositories\JuridictionRepository;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;

class JuridictionController extends Controller
{
    protected $juridictionRepository;

    public function __construct(JuridictionRepository $juridictionRepository){
        $this->juridictionRepository =$juridictionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $juridictions = $this->juridictionRepository->getAll();
        return view('juridiction.index',compact('juridictions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('juridiction.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $juridictions = $this->juridictionRepository->store($request->all());
        return redirect('juridiction');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $juridiction = $this->juridictionRepository->getById($id);
        return view('juridiction.show',compact('juridiction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $juridiction = $this->juridictionRepository->getById($id);
        return view('juridiction.edit',compact('juridiction'));
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
        $this->juridictionRepository->update($id, $request->all());
        return redirect('juridiction');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->juridictionRepository->destroy($id);
        return redirect('juridiction');
    }
    public function    allJuridictionapi(){
        $juridictions = $this->juridictionRepository->getAllOnLy();
        return response()->json($juridictions);
    }
    public function importExcel(Request $request)
{

   /*   $data =  Excel::import(new JuridictionImport,$request['file']);
 //   dd($data);

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
    $status = Juridiction::insert($rows->toArray());

    // Si toutes les lignes sont insérées
    if ($status) {

        // 5. On supprime le fichier uploadé
        $reader->close(); // On ferme le $reader
       // unlink($fichier);

        // 6. Retour vers le formulaire avec un message $msg
        return back()->withMsg("Importation réussie !");

    } else { abort(500); }
}

}
