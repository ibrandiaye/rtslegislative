<?php

namespace App\Http\Controllers;

use App\Repositories\PaysRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtscentreeRepository;
use App\Repositories\RtspaysRepository;
use Illuminate\Http\Request;
class ApiController extends Controller
{
    
        protected $rtscentreRepository;
        protected $rtscentreeRepository;
        protected $rtspaysRepository;
      
        public function __construct(
            RtscentreRepository $rtscentreRepository,
            RtscentreeRepository $rtscentreeRepository,RtspaysRepository $paysRepository
        ){
           
            $this->rtscentreRepository = $rtscentreRepository;
            $this->rtscentreeRepository = $rtscentreeRepository;
            $this->rtspaysRepository = $paysRepository;    
          
        }


        public function getResultatParCandidat(){
          
        $votants = $this->rtscentreRepository->nbVotants();

        $rtsParCandidats = $this->rtscentreRepository->rtsByCandidat();
            return json_encode(["nbrVotant"=>$votants,"rtsParCandidats"=>$rtsParCandidats]);
        }


        public  function getresultatsParCandidatsRegion(){
           $resultatByRegion = $this->rtscentreRepository->rstByRegieon();
           return json_encode($resultatByRegion);
        }

        public function getresultatsParCandidatsByRegionId(Request $request){
            $resultatByRegion = $this->rtscentreRepository->rstByRegieonId($request->id);
            return json_encode($resultatByRegion);
        }
        public function getRsByCandidats(Request $request){
            $dep = $this->rtscentreRepository->rtsByRegionDepartementByCentreByCandidat($request->id,$request->idregion);
            $com = $this->rtscentreRepository->rtsByRegionComuneByCentreByCandidat($request->id,$request->idregion);
            return ['dep'=>$dep,'com'=>$com];
        }

        public function getResultatParCandidatFromDispora(){
            $votants = $this->rtspaysRepository->nbVotants();

            $rtsParCandidats = $this->rtspaysRepository->rtsByCandidat();
                return json_encode(["nbrVotant"=>$votants,"rtsParCandidats"=>$rtsParCandidats]);
        } 
        public function getResultatByPays(){
            $votants = $this->rtspaysRepository->nbVotants();

            $rtsParCandidats = $this->rtspaysRepository->rtsByCandidat();
                return json_encode(["nbrVotant"=>$votants,"rtsParCandidats"=>$rtsParCandidats]);
        } 
        public function rtsGroupByRegionByCandidat($id){
            $rtsPardements = $this->rtsdepartementRepository->rtsGroupByRegionByCandidat($id);
            $nbVotant = $this->rtsdepartementRepository->nbVotants();
            foreach ($rtsPardements as $key => $value) {
                if($value->nb > 0 ){
                    $rtsPardements[$key]->nb = round((($value->nb/$nbVotant)*100),2);
                }
            } 
                return response()->json($rtsPardements);
        } 
       
}