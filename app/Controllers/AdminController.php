<?php
//******CETTE CLASSE NOUS PERMET DE GÉRER L'ADMINISTRATION*******/
namespace App\Controllers;

use App\Controllers\MainController;
use App\Models\ActivityModel;

class AdminController extends MainController
{
    public function renderAdmin(): void
    {

        $this->checkUserAuthorization(1);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // et si le formulaire est addPostForm
            if (isset($_POST["addPostForm"])) {
                //  on lance la méthode d'ajout d'article
                $this->addPost();
            }
            // si le formulaire est deletePostForm
            if (isset($_POST['deletePostForm'])) {
                //  on lance la méthode de suppression d'article
                $this->removePost();
            }
            // si le formulaire est updatePostForm
            if (isset($_POST['updatePostForm'])) {
                //  on lance la méthode de mise à jour d'article
                $this->updatePost();
            }
        }
        
        $this->viewType = 'admin';
        if (isset($this->subPage)) 
        {
            $this->view = $this->subPage;
        } else {
            // Sinon s'il n'ya pas de sous-page, on stocke dans la propriété data tous les articles pour les afficher dans la vue admin            
            $this->data['activities'] = ActivityModel::getActivities();
        //  dans tous les cas on appelle la méthode render du controller parent pour construire la page
        $this->render();
        }
    }
    
    //méthode pour ajouter un article
    public function addPost(): void
    {

        // filter_input est une fonction PHP
        // elle récupère une variable externe d'un champs de formulaire et la filtre
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
        // Les catégories son récupérées mais pas encore gérées
        $categories = filter_input(INPUT_POST, 'categories', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $thumbnail = filter_input(INPUT_POST, 'thumbnail', FILTER_SANITIZE_URL);
        // La date sera la date du jour lors de la création de l'article
        $date = date('Y-m-d');

        // On créé une nouvelle instance de PostModel
        $postModel = new PostModel();
        // puis on utilise les setters pour ajouter les valeurs au propriétés privée du postModel
        $postModel->setTitle($title);
        $postModel->setContent($content);
        $postModel->setImg($thumbnail);
        $postModel->setDate($date);

        // on déclenche l'instertion d'article dans une conditions car PDO va renvoyer true ou false
        if ($postModel->insertPost()) {
            // donc si la requête d'insertion s'est bien passée, on renvoie true et on stocke un message de succès dans la propriété data
            $this->data[] = '<div class="alert alert-success" role="alert">Article enregistré avec succès</div>';
        } else {
            // sinon, stocke un message d'erreur
            $this->data[] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
    }

    // méthode pour mettre à jour un article
    // cette méthode est très similaire à addPost. 
    // On à deux solutions soit faire une seul méthode pour avoir un seul code pour les deux traitements.
    // ou bien justement séparer les traitements pour avoir deux méthodes distinctes quitte à avoir du code similaire
    // j'ai préféré faire ce choix

    public function updatePost(): void
    {

        $id = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
        $categories = filter_input(INPUT_POST, 'categories', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $thumbnail = filter_input(INPUT_POST, 'thumbnail', FILTER_SANITIZE_URL);
        $date = date('Y-m-d');

        $postModel = new PostModel();
        $postModel->setId($id);
        $postModel->setTitle($title);
        $postModel->setContent($content);
        $postModel->setImg($thumbnail);
        $postModel->setDate($date);


        if ($postModel->updatePost()) {
            $this->data['infos'] = '<div class="alert alert-success" role="alert">Article enregistré avec succès</div>';
        } else {
            $this->data['infos'] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
    }

    // méthode de suppresion d'un article
    public function removePost(): void
    {
        // récupération et filtrage du champs 
        $postId = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_SPECIAL_CHARS);

        if (PostModel::deletePost($postId)) {
            $this->data['infos'] = '<div class="alert alert-success d-inline-block mx-4" role="alert">Article supprimé avec succès</div>';
        } else {
            $this->data['infos'] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
    }
}