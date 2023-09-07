<?php

namespace App\Controllers;

class MainController {
    protected $view; 
    protected $id; 
    protected $data;
    protected string $viewType = 'front'; 
    
    public function render() { 
        $base_uri = explode('/public/',$_SERVER['REQUEST_URI']);
        // var_dump ($base_uri);
        
        $data = $this->data;
        require __DIR__.'/../views/front/layouts/header.phtml';
        require __DIR__."/../views/front/partials/".$this->view.".phtml"; // this fait ref à une des propriete protected
        require __DIR__.'/../views/front/layouts/footer.phtml';
    }

    // Méthode permettant de vérifier si l'utilisateur est authorisé à accéder à la page
    // On passe le rôle demandé en param
    protected function checkUserAuthorization(int $role): bool
    {
        // S'il y'a une session user
        if (isset($_SESSION['userObject'])) {
            // on stocke les données de la session dans une variable
            $currentUser = $_SESSION['userObject'];
            //  on récupère le rôle
            $currentUserRole = $currentUser->getRole();
            // Si le rôle est inférieur ou égal au role demandé (le rôle 1 est le plus haut)
            if ($currentUserRole <= $role) {
                // on renvoie true
                return true;
            } else {
                // sinon, on renvoie un code d'erreur 403
                http_response_code(403);
                // on alimente la propriété view avec la vue 403
                $this->view = '403';
                // on construit la page
                $this->render();
                // on arrête le script
                exit();
            }
        } else {
            // sinon s'il n'ya pas de session user
            // on créer une url de redirection
            $redirect = explode('/public/', $_SERVER['REQUEST_URI']);
            // on redirige vers la page de connexion
            header('Location: ' . $redirect[0] . '/public/login');
            // on arrête le script
            exit();
        }
    }
    
    public function getView() { // un getter retourne tjs la valeur de la propriete
        return $this->view;
    }
    
    public function setView($View) { // permet de mod la vue
        $this->view = $View  ;
    }
    
     public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function setData($data) {
        $this->data = $data;
    }

    public function getActivity($limit) {
        return $this->activity;
    }

}

?>