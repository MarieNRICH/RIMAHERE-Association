<?php

namespace App\Controllers;

use App\Controllers\MainController;
use App\Models\ActivityModel;

class ActivityController extends MainController
{

    public function renderActivity(): void
    {
        // on alimente la propriété data avec l'article 
        $this->data["activities"] = ActivityModel::getActivityById(1);
        // on construit la page
        $this->render();
    }
    
    // méthode pour ajouter une activité
    public function addActivity(): void
    {
        $date =  date('Y-m-d');
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $nivel = filter_input(INPUT_POST, 'nivel', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        
        $activityModel = new ActivityModel();
        // puis on utilise les setters pour ajouter les valeurs au propriétés privée du activityModel
        
        $activityModel->setDate($date);
        $activityModel->setName($name);
        $activityModel->setContent($description);
        $activityModel->setNivel($nivel);
        $activityModel->setUserId($userId);
        
        // on déclenche l'instertion d'article dans une conditions car PDO va renvoyer true ou false
        if ($activityModel->insertActivity()) {
            // donc si la requête d'insertion s'est bien passée, on renvoie true et on stocke un message de succès dans la propriété data
            $this->data[] = '<div class="alert alert-success" role="alert">Article enregistré avec succès</div>';
        } else {
            // sinon, stocke un message d'erreur
            $this->data[] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
        
    }
    
    public function updateActivity(): void
    {

        $id = filter_input(INPUT_POST, 'activityid', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $nivel = filter_input(INPUT_POST, 'categories', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $date = date('Y-m-d');

        $activityModel = new ActivityModel();
        $activityModel->setId($id);
        $activityModel->setName($name);
        $activityModel->setContent($description);
        $activityModel->setNivel($nivel);
        $activityModel->setDate($date);
        $activityModel->setUserId($userId);


        if ($activityModel->updateActivity()) {
            $this->data['infos'] = '<div class="alert alert-success" role="alert">Article enregistré avec succès</div>';
        } else {
            $this->data['infos'] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
    }

    // méthode de suppresion d'un article
    public function removeActivity(): void
    {
        // récupération et filtrage du champs 
        $activityId = filter_input(INPUT_POST, 'activityid', FILTER_SANITIZE_SPECIAL_CHARS);

        if (ActivityModel::deleteActivity($activityId)) {
            $this->data['infos'] = '<div class="alert alert-success d-inline-block mx-4" role="alert">Article supprimé avec succès</div>';
        } else {
            $this->data['infos'] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
    }
    
}