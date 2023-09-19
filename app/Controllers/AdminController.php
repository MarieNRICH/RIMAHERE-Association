<?php
//******CETTE CLASSE NOUS PERMET DE GÉRER L'ADMINISTRATION*******/
namespace App\Controllers;

use App\Controllers\MainController;
use App\Models\ActivityModel;
use App\Models\ContactModel;

class AdminController extends MainController
{
    public function renderAdmin(): void
    {

        $this->checkUserAuthorization(1);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // et si le formulaire est addActivityForm
            if (isset($_POST["addActivityForm"])) {
                //  on lance la méthode d'ajout d'article
                $this->addActivity();
            }
            // si le formulaire est deleteActivityForm
            if (isset($_POST['deleteActivityForm'])) {
                //  on lance la méthode de suppression d'article
                $this->removeActivity();
            }
            // si le formulaire est updateActivityForm
            if (isset($_POST['updateActivityForm'])) {
                //  on lance la méthode de mise à jour d'article
                $this->updateActivity();
            }
        }
        
        $this->viewType = 'admin';
        if($this->view === 'adminMessage'){
            $contactModel = new ContactModel();
            $messages = $contactModel->getMessages();
            $this->data['messages'] = $messages;
        }
        if (isset($this->subPage)) 
        {
            $this->view = $this->subPage;
        } else {
            // Sinon s'il n'y a pas de sous-page, on stocke dans la propriété data tous les articles pour les afficher dans la vue admin            
            $this->data['activities'] = ActivityModel::getActivities();
        //  dans tous les cas on appelle la méthode render du controller parent pour construire la page
        $this->render();
        }
    }
    
    //méthode pour ajouter une activité
    public function addActivity(): void
    {

        // filter_input est une fonction PHP
        // elle récupère une variable externe d'un champs de formulaire et la filtre
        $date = date('Y-m-d');
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        // Les catégories son récupérées mais pas encore gérées
        $nivel = filter_input(INPUT_POST, 'categories', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $thumbnail = filter_input(INPUT_POST, 'thumbnail', FILTER_SANITIZE_URL);

        // On créé une nouvelle instance de ActivityModel
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

    // méthode pour mettre à jour un article
    // cette méthode est très similaire à addActivity. 
    // On à deux solutions soit faire une seul méthode pour avoir un seul code pour les deux traitements.
    // ou bien justement séparer les traitements pour avoir deux méthodes distinctes quitte à avoir du code similaire
    // j'ai préféré faire ce choix

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