<?php

namespace App\Http\Controllers;

use App\Repositories\PaysRepository;
use App\Repositories\RtsdepartementRepository;
use App\Repositories\RtscentreeRepository;
use App\Repositories\RtspaysRepository;
use Illuminate\Http\Request;
class ApiDeptController extends Controller
{
    
        protected $rtsdeptRepository;
        protected $rtscentreeRepository;
        protected $rtspaysRepository;
      
        public function __construct(
            RtsdepartementRepository $rtsdeptRepository,
            RtscentreeRepository $rtscentreeRepository,RtspaysRepository $paysRepository
        ){
           
            $this->rtsdeptRepository = $rtsdeptRepository;
            $this->rtscentreeRepository = $rtscentreeRepository;
            $this->rtspaysRepository = $paysRepository;    
          
        }


        public function getResultatParCandidat(){
          
        $votants = $this->rtsdeptRepository->nbVotants();

        $rtsParCandidats = $this->rtsdeptRepository->rtsByCandidat();
            return json_encode(["nbrVotant"=>$votants,"rtsParCandidats"=>$rtsParCandidats]);
        }


        public  function getresultatsParCandidatsRegion(){
           $resultatByRegion = $this->rtsdeptRepository->rstByRegieon();
           return json_encode($resultatByRegion);
        }

        public function getresultatsParCandidatsByRegionId(Request $request){
            $resultatByRegion = $this->rtsdeptRepository->rstByRegieonId($request->id);
            return json_encode($resultatByRegion);
        }
        public function getRsByCandidats(Request $request){
            $dep = $this->rtsdeptRepository->rtsByRegionDepartementByDepartementByCandidat($request->id);
      
            return ['dep'=>$dep];
        }

        public function getResultatParCandidatFromDispora(){
            $votants =  $this->rtspaysRepository->nbVotants();

            $rtsParCandidats =    $this->rtspaysRepository->rtsByCandidat();
                return json_encode(["nbrVotant"=>$votants,"rtsParCandidats"=>$rtsParCandidats]);
        } 


    


        public function getResultatByPays(){
            $votants = $this->rtspaysRepository->nbVotants();

            $rtsParCandidats = $this->rtspaysRepository->rtsByCandidat();
                return json_encode(["nbrVotant"=>$votants,"rtsParCandidats"=>$rtsParCandidats]);
        } 
        public function rtsGroupByRegionByCandidat($id){
            $rtsPardements = $this->rtsdeptRepository->rtsGroupByRegionByCandidat($id);
            $nbVotants = $this->rtsdeptRepository->rtsVotantGroupByRegion();
            foreach ($rtsPardements as $key => $value) {
                foreach ($nbVotants as $k => $v) {
                    if($value->nb > 0  && $value->region== $v->region) {
                        $rtsPardements[$key]->nb = round((($value->nb/$v->nb)*100),2);
                    }
                }
              
            } 
                return response()->json($rtsPardements);
        } 
       
}