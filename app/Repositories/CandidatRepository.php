<?php
namespace App\Repositories;

use App\Models\Candidat;
use App\Repositories\RessourceRepository;

class CandidatRepository extends RessourceRepository{
    public function __construct(Candidat $candidat){
        $this->model = $candidat;
    }
}
