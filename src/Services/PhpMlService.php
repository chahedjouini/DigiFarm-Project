<?php

namespace App\Service;

use Phpml\Classification\KNearestNeighbors;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Culture;
use App\Entity\Etude;

class PhpMlService
{
    private $entityManager;
    private $classifier;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->loadModel();
    }

    private function getTrainingData(): array
    {
        // Récupérer toutes les cultures
        $cultures = $this->entityManager->getRepository(Culture::class)->findAll();
        
        $samples = [];
        $targets = [];
    
        foreach ($cultures as $culture) {
            // Extraire les informations de la culture
            $temperature = $culture->getBesoinsEau();  // Exemple
            $densitePlantation = $culture->getDensitePlantation();
            
            // Récupérer les études associées à chaque culture
            foreach ($culture->getEtudes() as $etude) {
                // Extraire les informations de l'étude
                $facteurInfluence = $etude->getMainOeuvre(); // Exemple
    
                // Ajouter la variable $precipitations
                $precipitations = $etude->getPrecipitations(); // Utilisation correcte de la méthode getPrecipitations()
    
                // Combiner les données de la culture et de l'étude
                $samples[] = [
                    $temperature, 
                    $precipitations, 
                    $densitePlantation,
                    $facteurInfluence
                ];
    
                // Ajouter le rendement comme target (sortie attendue)
                $targets[] = $etude->getRendement();
            }
        }
    
        return [$samples, $targets];
    }

    private function minMaxScaler($data)
    {
        // Transposer les données pour faciliter le calcul min/max sur chaque caractéristique (colonne)
        $transposed = array_map(null, ...$data);

        // Calculer les min et max pour chaque colonne
        $min = array_map('min', $transposed);
        $max = array_map('max', $transposed);

        // Calculer la plage pour chaque colonne
        $range = array_map(function($min, $max) {
            return ($max - $min); // Plage = max - min
        }, $min, $max);

        // Log pour vérifier les min, max et ranges
        foreach ($range as $key => $r) {
            if ($r === 0) {
                // Log pour voir où la division par zéro pourrait arriver
                echo "Colonne $key a une plage de zéro (min = {$min[$key]}, max = {$max[$key]})\n";
            }
        }

        // Vérifier que range n'est pas zéro pour éviter une division par zéro
        $range = array_map(function($r) {
            return $r == 0 ? 1 : $r; // Remplacer les plages égales à zéro par 1
        }, $range);

        // Appliquer la mise à l'échelle Min-Max sur les données
        $scaledData = array_map(function($row) use ($min, $range) {
            return array_map(function($val, $min, $range) {
                return ($val - $min) / $range; // Appliquer Min-Max Scaling
            }, $row, $min, $range);
        }, $data);

        return $scaledData;
    }
    

    private function loadModel()
    {
        if (file_exists('model.phpml')) {
            $this->classifier = unserialize(file_get_contents('model.phpml'));
        } else {
            $this->classifier = new KNearestNeighbors();
        }
    }

    public function trainModel(): void
    {
        // Récupérer les données de la base de données (Culture + Etude)
        list($samples, $targets) = $this->getTrainingData();

        // Vérification des données avant de procéder à l'échelle
        if (empty($samples)) {
            echo "Aucune donnée de formation disponible.\n";
            return;
        }

        // Appliquer Min-Max Scaling sur les données
        $samples = $this->minMaxScaler($samples);

        // Entraîner le modèle KNN
        $this->classifier->train($samples, $targets);
    
        // Sauvegarder le modèle pour l'utiliser plus tard
        file_put_contents('model.phpml', serialize($this->classifier));
    }

    // src/Service/PhpMlService.php

public function predict(array $newData): float
{
    // Apply scaling to the new data before predicting
    $newData = $this->minMaxScaler([$newData])[0];

    // Perform prediction using the classifier (KNN model)
    return $this->classifier->predict($newData);
}


    public function showDataset(): void
{
    // Récupérer les données de la base de données (Culture + Etude)
    list($samples, $targets) = $this->getTrainingData();

    // Afficher les données d'entraînement
    echo "Samples (données d'entraînement) :\n";
    print_r($samples); // Afficher les échantillons

    echo "Targets (cibles) :\n";
    print_r($targets); // Afficher les cibles (rendement)
}

}
