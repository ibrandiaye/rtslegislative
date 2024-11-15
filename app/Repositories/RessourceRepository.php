<?php
namespace App\Repositories;

class RessourceRepository {
    protected $model;

    public function getPaginate($n)
    {
        return $this->model->paginate($n);
    }
    public  function getAll(){
        return $this->model->get();
    }

    public function store(Array $inputs)
    {
        return $this->model->create($inputs);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, Array $inputs)
    {
        $this->getById($id)->update($inputs);
    }

    public function destroy($id)
    {
        $this->getById($id)->delete();
    }
    public function diffBetweenTwoDateMin($datedepart, $datearrive){


// Declare and define two dates
        $date1 = strtotime($datedepart);
        $date2 = strtotime($datearrive);

// Formulate the Difference between two dates
        $diff = abs($date2 - $date1);


// To get the year divide the resultant date into
// total seconds in a year (365*60*60*24)
        $years = floor($diff / (365*60*60*24));


// To get the month, subtract it with years and
// divide the resultant date into
// total seconds in a month (30*60*60*24)
        $months = floor(($diff - $years * 365*60*60*24)
            / (30*60*60*24));


// To get the day, subtract it with years and
// months and divide the resultant date into
// total seconds in a days (60*60*24)
        $days = floor(($diff - $years * 365*60*60*24 -
                $months*30*60*60*24)/ (60*60*24));


// To get the hour, subtract it with years,
// months & seconds and divide the resultant
// date into total seconds in a hours (60*60)
        $hours = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24)
            / (60*60));


// To get the minutes, subtract it with years,
// months, seconds and hours and divide the
// resultant date into total seconds i.e. 60
        $minutes = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                - $hours*60*60)/ 60);


// To get the minutes, subtract it with years,
// months, seconds, hours and minutes
        $seconds = floor(($diff - $years * 365*60*60*24
            - $months*30*60*60*24 - $days*60*60*24
            - $hours*60*60 - $minutes*60));
        return $seconds;

    }
  /*  function calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants) {
        $resultatSiegesMajoritaires = [];
        $siegesProportionnels = [];
        $siegesProp = 53;  // Nombre de sièges pour la proportionnelle

        // Distribution des sièges majoritaires : tout au parti ayant la majorité des voix
        foreach ($circonscriptions as $circonscription => $resultats) {
            arsort($resultats);  // Trier les votes par ordre décroissant pour la circonscription
            $partiGagnant = key($resultats);  // Parti ayant obtenu le plus de votes
            $nombreSieges = $siegesParCirconscription[$circonscription] ?? 0;

            // Attribuer tous les sièges de la circonscription au parti gagnant
            $resultatSiegesMajoritaires[$partiGagnant] = ($resultatSiegesMajoritaires[$partiGagnant] ?? 0) + $nombreSieges;
        }

        // Calcul du quotient électoral pour la répartition proportionnelle
        $quotientElectoral = $totalVotants / $siegesProp;

        // Distribution initiale des sièges proportionnels
        foreach ($votesProportionnels as $parti => $votes) {
            $siegesProportionnels[$parti] = intdiv($votes, $quotientElectoral);
        }

        // Vérification du total initial des sièges attribués
        $siegesAttribues = array_sum($siegesProportionnels);
        $siegesRestants = $siegesProp - $siegesAttribues;

        if ($siegesRestants > 0) {
            // Calculer les restes pour chaque parti
            $restes = [];
            foreach ($votesProportionnels as $parti => $votes) {
                $reste = $votes % $quotientElectoral;
                $restes[$parti] = $reste;
            }

            // Trier les partis par ordre décroissant des restes
            arsort($restes);

            // Distribuer les sièges restants aux partis avec les plus grands restes
            foreach (array_keys($restes) as $parti) {
                if ($siegesRestants <= 0) break;  // Stopper dès que tous les sièges sont attribués
                $siegesProportionnels[$parti]++;
                $siegesRestants--;
            }
        }

        // Ajuster strictement au cas où il y aurait encore un dépassement
        while (array_sum($siegesProportionnels) > $siegesProp) {
            foreach ($siegesProportionnels as $parti => &$sieges) {
                if ($sieges > 0) {
                    $sieges--;
                    if (array_sum($siegesProportionnels) == $siegesProp) break 2;
                }
            }
        }

        // Combinaison des résultats
        $siegesTotal = [];
        foreach (array_merge(array_keys($resultatSiegesMajoritaires), array_keys($siegesProportionnels)) as $parti) {
           // $siegesTotal[$parti] = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
           $siege = array();
           $siege['proportionnel'] =  $siegesProportionnels[$parti] ?? 0;
           $siege['majoritaire']   = $resultatSiegesMajoritaires[$parti] ?? 0;
           $siege['total']         = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
            $siegesTotal[$parti] = $siege;
        }

        return $siegesTotal;
    }*/
    public function calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants) {
        $resultatSiegesMajoritaires = [];
        $siegesProportionnels = [];
        $siegesProp = 53;  // Nombre de sièges pour la proportionnelle

        // Distribution des sièges majoritaires : tout au parti ayant la majorité des voix
        foreach ($circonscriptions as $circonscription => $resultats) {
            arsort($resultats);  // Trier les votes par ordre décroissant pour la circonscription
            $partiGagnant = key($resultats);  // Parti ayant obtenu le plus de votes
            $nombreSieges = $siegesParCirconscription[$circonscription] ?? 0;

            // Attribuer tous les sièges de la circonscription au parti gagnant
            $resultatSiegesMajoritaires[$partiGagnant] = ($resultatSiegesMajoritaires[$partiGagnant] ?? 0) + $nombreSieges;
        }

        // Calcul du quotient électoral pour la répartition proportionnelle
        $quotientElectoral = $totalVotants / $siegesProp;
       //dd($quotientElectoral);

        // Distribution des sièges proportionnels en fonction du quotient électoral
        foreach ($votesProportionnels as $parti => $votes) {
            $siegesProportionnels[$parti] = intdiv($votes, $quotientElectoral);
        }

        // Attribution des sièges restants par la méthode des plus forts restes
        $siegesAttribues = array_sum($siegesProportionnels);
        $siegesRestants = $siegesProp - $siegesAttribues;

        if ($siegesRestants > 0) {
            // Calculer les restes pour chaque parti
            $restes = [];
            foreach ($votesProportionnels as $parti => $votes) {
                $reste = $votes % $quotientElectoral;
                $restes[$parti] = $reste;
            }

            // Trier les partis par ordre décroissant des restes
            arsort($restes);

            // Distribuer les sièges restants aux partis avec les plus grands restes
            foreach (array_keys($restes) as $parti) {
                if ($siegesRestants <= 0) break;
                $siegesProportionnels[$parti]++;
                $siegesRestants--;
            }
        }

        // Combinaison des résultats
        $siegesTotal = array();
          foreach (array_merge(array_keys($resultatSiegesMajoritaires), array_keys($siegesProportionnels)) as $parti) {
                $siege = array();
                $siege['proportionnel'] =  $siegesProportionnels[$parti] ?? 0;
                $siege['majoritaire']   = $resultatSiegesMajoritaires[$parti] ?? 0;
                $siege['total']         = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
              $siegesTotal[$parti] = $siege;
          }

          return $siegesTotal;
      }

}
