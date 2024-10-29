<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\CarteController;
use App\Http\Controllers\CentrevoteController;
use App\Http\Controllers\CollecteurControlleur;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LieuvoteController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RtscentreController;
use App\Http\Controllers\RtslieuController;
use App\Http\Controllers\ApiDeptController;
use App\Repositories\RtscentreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/verifier/collecteur',[CollecteurControlleur::class,'verifierCollecteur'] )->name("home")->middleware("cors");
Route::get('/regions/all',[RegionController::class,'allRegionapi'] );
Route::get('/departement/by/region/{region}',[DepartementController::class,'byRegion'] )->middleware("cors");
Route::get('/commune/by/departement/{departement}',[CommuneController::class,'byDepartement'] )->middleware("cors");
//Route::get('/nb/carte/un/collecteur/{id}',[CarteController::class,'carteByOneCollecteur'] );


Route::get('/centre/by/commune/{commune}',[CentrevoteController::class,'getBycommune'] )->middleware("cors");
Route::get('/lieu/by/centre/{centrevote}',[LieuvoteController::class,'getByCentreVote'] )->middleware("cors");

Route::get('/all/candidat',[CandidatController::class,'allCandidat'] )->middleware("cors");
Route::post('/enregistrer/resultat',[RtslieuController::class,'storeApi'] )->name("home")->middleware("cors");
Route::get('/departements/all',[DepartementController::class,'allDepartementApi'] )->middleware("cors");
Route::get('/communes/all',[CommuneController::class,'allCommuneApi'] )->middleware("cors");
Route::get('/centrevotes/all',[CentrevoteController::class,'allCentrevoteApi'] )->middleware("cors");
Route::get('/lieuvotes/all',[LieuvoteController::class,'allLieuvoteApi'] )->middleware("cors");

Route::get('/communes/by/nom',[CommuneController::class,'getCommuneByNom'] )->middleware("cors");

Route::get('/resultatsParCandidatsFromDiapora',[ApiDeptController::class,'getResultatParCandidatFromDispora']);
Route::get('/resultatsParCandidats/{id}/{idregion}',[ApiDeptController::class,'getRsByCandidats']);
Route::get('/resultatsParCandidats',[ApiDeptController::class,'getResultatParCandidat'])->middleware("cors");
Route::get('/resultatsParCandidatsRegion',[ApiDeptController::class,'getresultatsParCandidatsRegion'])->middleware("cors");
Route::get('/resultatsParCandidatsByRegionId/{id}',[ApiDeptController::class,'getresultatsParCandidatsByRegionId'])->middleware("cors");
Route::get('/resultatsParCandidatsFromDiapora',[ApiDeptController::class,'getResultatParCandidatFromDispora'])->middleware("cors");
Route::get('/resultatsParCandidats/{id}',[ApiDeptController::class,'getRsByCandidats']);
Route::get('/rts/groupby/candidat/and/region',[RtscentreController::class,'resultatByRegionByCandidat'])->middleware("cors");

Route::get('/rts/pays',[ApiDeptController::class,'getResultatByPays'])->middleware("cors");

Route::get('/all/candidats',[CandidatController::class,'allCandidats'])->middleware("cors");
Route::get('/rts/region/candidat/{id}',[ApiDeptController::class,'rtsGroupByRegionByCandidat'])->middleware("cors");
Route::get('/data/home',[HomeController::class,'apiDashbord'])->middleware("cors");
