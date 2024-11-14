<?php

use App\Http\Controllers\ArrondissementController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\CentrevoteController;
use App\Http\Controllers\CentrevoteeController;
use App\Http\Controllers\CollecteurControlleur;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\HeureController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JuridictionController;
use App\Http\Controllers\LieuvoteController;
use App\Http\Controllers\LieuvoteeController;
use App\Http\Controllers\LocaliteController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\RtscentreeController;
use App\Http\Controllers\RtsCommuneController;
use App\Http\Controllers\RtsDepartementontroller;
use App\Http\Controllers\RtslieueController;
use App\Http\Controllers\RtspaysController;
use App\Http\Controllers\RtstemoinController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RtscentreController;
use App\Http\Controllers\RtslieuController;
use App\Http\Controllers\SondageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/resultat/par/centre',[HomeController::class,'indexCentre'])->name("index.centre")->middleware("auth");
Route::get('/',[HomeController::class,'index'])->name("home")->middleware("auth");
Route::resource('region', RegionController::class)->middleware("auth");
Route::resource('departement', DepartementController::class)->middleware("auth");
Route::resource('commune', CommuneController::class)->middleware("auth");
Route::resource('arrondissement', ArrondissementController::class)->middleware("auth");

Route::resource('candidat', CandidatController::class)->middleware("auth");
Route::resource('centrevote', CentrevoteController::class)->middleware("auth");
Route::resource('lieuvote', LieuvoteController::class)->middleware("auth");
Route::resource('rtscentre', RtscentreController::class)->middleware("auth");
Route::resource('rtslieu', RtslieuController::class)->middleware("auth");
Route::resource('rtscentree', RtscentreeController::class)->middleware("auth");
Route::resource('rtslieue', RtslieueController::class)->middleware("auth");
Route::resource('heure', HeureController::class)->middleware("auth");
Route::resource('participation', ParticipationController::class)->middleware("auth");
Route::resource('rtstemoin', RtstemoinController::class)->middleware("auth");
Route::resource('rtspays', RtspaysController::class)->middleware("auth");
Route::resource('rtscommune', RtsCommuneController::class)->middleware("auth");
Route::resource('rtsdepartement', RtsDepartementontroller::class)->middleware("auth");
Route::resource('user', controller: UserController::class)->middleware("auth");
Route::resource('bureau', controller: BureauController::class)->middleware("auth");

Route::get('lieuvote/bureau/create/{id}/{commune}', [BureauController::class,'createByLieuVote'])->name("lieuvote.bureau.create")->middleware("auth");
Route::get('lieuvote/destroy/destroy/{id}', [BureauController::class,'destroyByLieuVote'])->name("destroy.by.lieuvote")->middleware("auth");
Route::get('bureau/by/lieuvote/{id}', [BureauController::class,'getByLieuVote'])->name("bureau.by.lieuvote")->middleware("auth");



Route::get('/participation/by/heure/{heure}',[ParticipationController::class,'getParticipationByHeure'])->name("participation.heure")->middleware("auth");

Route::post('/update/password',[UserController::class,'updatePassword'])->name("user.password.update")->middleware(["auth"]);


Route::post('/importer/region',[RegionController::class,'importExcel'])->name("importer.region")->middleware("auth");
Route::resource('collecteur', CollecteurControlleur::class)->middleware("auth");

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware("auth");
Route::get('/debartement/by/region/{region}',[DepartementController::class,'byRegion'])->name("departement.by.region")->middleware("auth");
Route::get('/commune/by/departement/{nom}',[CommuneController::class,'byDepartement'])->name("commune.by.departement")->middleware("auth");

Route::post('/importer/departement',[DepartementController::class,'importExcel'])->name("importer.departement")->middleware("auth");
Route::post('/importer/commune',[CommuneController::class,'importExcel'])->name("importer.commune")->middleware("auth");
Route::post('/importer/arrondissement',[ArrondissementController::class,'importExcel'])->name("importer.arrondissement")->middleware("auth");

Route::post('/importer/centrevote',[CentrevoteController::class,'importExcel'])->name("importer.centrevote")->middleware("auth");
Route::post('/importer/lieuvote',[LieuvoteController::class,'importExcel'])->name("importer.lieuvote")->middleware("auth");
Route::get('/resultat',[HomeController::class,'resultat'])->name("resultat");
Route::resource('/sondage',SondageController::class);

Route::get('/departement/by/region/{region}',[DepartementController::class,'getByRegion'])->name("departement.by.region");
Route::get('/commune/by/departement/{departement}',[CommuneController::class,'getByDepartement']);
Route::get('/centrevote/by/commune/{commune}',[CentrevoteController::class,'getBycommune']);
Route::get('/centrevote/by/temoin/commune/{commune}',[CentrevoteController::class,'getByCommuneAndTemoin']);


Route::get('/lieuvote/by/centrevote/{centrevote}',[LieuvoteController::class,'getByCentreVote']);
Route::get('/lieuvote/temoin/by/centrevote/{centrevote}',[LieuvoteController::class,'getTemoinByCentreVote']);
Route::get('/lieuvote/temoin/participation/by/centrevote/{centrevote}',[LieuvoteController::class,'getByLieuvoteTemoinParticipation']);


Route::get('/commune/by/arrondissement/{arrondissement}',[CommuneController::class,'getByArrondissement']);


Route::get('/arrondissement/by/departement/{departement}',[ArrondissementController::class,'byDepartement'])->name("arrondissement.by.departement");


Route::resource('juridiction', JuridictionController::class)->middleware("auth");
Route::resource('pays', PaysController::class)->middleware("auth");
Route::resource('localite', LocaliteController::class)->middleware("auth");
Route::resource('centrevotee', CentrevoteeController::class)->middleware("auth");
Route::resource('lieuvotee', LieuvoteeController::class)->middleware("auth");

Route::post('/importer/juridiction',[JuridictionController::class,'importExcel'])->name("importer.juridiction")->middleware("auth");
Route::post('/importer/pays',[PaysController::class,'importExcel'])->name("importer.pays")->middleware("auth");
Route::post('/importer/localite',[LocaliteController::class,'importExcel'])->name("importer.localite")->middleware("auth");
Route::post('/importer/centrevotee',[CentrevoteeController::class,'importExcel'])->name("importer.centrevotee")->middleware("auth");
Route::post('/importer/lieuvotee',[LieuvoteeController::class,'importExcel'])->name("importer.lieuvotee")->middleware("auth");


Route::get('/pays/by/juridiction/{juridiction}',[PaysController::class,'getByJuridiction'])->name("pays.by.juridiction");
Route::get('/localite/by/pays/{pays}',[LocaliteController::class,'getByPays']);
Route::get('/centrevotee/by/localite/{localite}',[CentrevoteeController::class,'getBylocalite']);
Route::get('/lieuvotee/by/centrevotee/{centrevotee}',[LieuvoteeController::class,'getByCentrevotee']);

Route::get('/somme/electeur/by/centrevote/{id}',[CentrevoteController::class,'sumElecteurByCentre']);

Route::get('/electeur/by/lieuvote/{id}',[LieuvoteController::class,'getById']);


Route::get('/somme/electeur/by/centrevotee/{id}',[CentrevoteeController::class,'sumElecteurByCentrevote']);

Route::get('/electeur/by/lieuvotee/{id}',[LieuvoteeController::class,'sommeByLieuvotee']);


Route::post('/importer/temoin',[LieuvoteController::class,'importTemoin'])->name("importer.temoin")->middleware("auth");

Route::get('/centrevote-temoin/by/commune/{commune}',[RtstemoinController::class,'getCentreTemoin']);
Route::get('/lieuvote-temoin/by/centrevote/{lieuvote}',[RtstemoinController::class,'getByLieuvoteTemoin']);

Route::get('/lieuvote-temoin/by/departement/{departement}',[ParticipationController::class,'nbElecteursTemoinByDepartement']);

Route::get('/departement/participation/{departement}',[ParticipationController::class,'getByRegionParticipation']);

Route::get('/sum/by/pays/{pays}',[LieuvoteeController::class,'sommeByPays']);

Route::get('/somme/electeur/by/commune/{commune}',[LieuvoteController::class,'sumElecteurByCommune']);

Route::get('/somme/electeur/by/departement/{departement}',[LieuvoteController::class,'sumElecteurByDepartement']);
Route::get('/stat/nb',[HomeController::class,'nbVoteStat'])->name('nbVoteStat')->middleware("auth");

Route::get('/doc/bureau/{id}',[BureauController::class,'docParBureau'])->name('doc.bureau')->middleware("auth");

Route::get('/centrevote/by/arrondissement',[CentrevoteController::class,'centreByLocalite'])->name('centre.by.arrondissement')->middleware("auth");

Route::get('/bureau/by/centrevote/{id}',[LieuvoteController::class,'getByCentrevotePrefer'])->name('lieu.vote.by.centre')->middleware("auth");

Route::get('/doc/centrevote/{id}',[BureauController::class,'docParCentre'])->name('doc.centre')->middleware("auth");



Route::post('/searh-arrondissement',[CentrevoteController::class,'searhArrondissement'])->name("searhArrondissement")->middleware("auth");

Route::post('/searh-departement',[CentrevoteController::class,'searhDepartement'])->name("searhDepartement")->middleware("auth");
Route::post('/searh-region',action: [CentrevoteController::class,'searhRegion'])->name("searhRegion")->middleware("auth");

Route::post('/searh-admin',action: [CentrevoteController::class,'searhAdmin'])->name("searhAdmin")->middleware("auth");
Route::get('/chercher/bureau',[BureauController::class,'chercherBureau'])->name('chercher.bureau')->middleware("auth");

Route::post('/search/tel',action: [BureauController::class,'searchTel'])->name("search.tel")->middleware("auth");

Route::post('/rts/departement',[RtslieuController::class,'rtsDepartement'])->name('rts.by.departement')->middleware("auth");

Route::get('/resultat/par/departement',[RtslieuController::class,'resultatParDepartement'])->name('voir.par.departement')->middleware("auth");

Route::get('/resultat/national',[RtslieuController::class,'rtsByCandidat'])->name('rts.national')->middleware("auth");

Route::get('/resultat/national/temoin',[RtslieuController::class,'rtsByBureatTemoin'])->name('rts.national.temoin')->middleware("auth");


Route::post('/rts/departement/temoin',[RtslieuController::class,'rtsDepartementTemoin'])->name('rts.by.departement.temoin')->middleware("auth");

Route::get('/resultat/par/departement/temoin',[RtslieuController::class,'resultatParDepartementTemoin'])->name('voir.par.departement.temoin')->middleware("auth");

Route::get('/resultat/par/departement/temoin/prefet',[RtslieuController::class,'rtsDepartementTemoinPrefet'])->name('voir.par.departement.temoin.prefet')->middleware("auth");


Route::get('/bureau/by/departement',[LieuvoteController::class,'getByDepartement'])->name('bureau.by.departement')->middleware("auth");

Route::get('/editer/rtslieu/{centrevoteId}/{lieuvoteId}/{communeId}',[RtslieuController::class,'showEdit'])->name('editer.rtslieu')->middleware("auth");

Route::post('/update/Perso/rtslieu',[RtslieuController::class,'updatePerso'])->name('update.perso.rtslieu')->middleware("auth");

Route::get('/show/rtslieu/by/lieu/{lieuvote}',[RtslieuController::class,'showRtsByLieuvote'])->name('show.rtslieu.by.lieu')->middleware("auth");

Route::post('/search/multi',[LieuvoteController::class,'search'])->name('search.multi')->middleware("auth");

Route::get('/impression/rts/departement/{departement}/{type}',[RtslieuController::class,'rtsDepartementImpression'])->name('impression.rts.departement')->middleware("auth");

Route::get('/impression/rts/national/{type}',[RtslieuController::class,'rtsByCandidatImpression'])->name('impression.rts.national')->middleware("auth");



Route::get('/bureau/by/national',[LieuvoteController::class,'getAllAndEtat'])->name('bureau.by.national')->middleware("auth");
Route::post('/search/national',[LieuvoteController::class,'searchNational'])->name('search.national')->middleware("auth");
Route::get('/mettre/bureau/temoin/{id}',[LieuvoteController::class,'mettreBureauTemoin'])->name('mettre.en.temoin')->middleware("auth");

Route::post('/search/participation',[ParticipationController::class,'search'])->name('search.participation')->middleware("auth");

