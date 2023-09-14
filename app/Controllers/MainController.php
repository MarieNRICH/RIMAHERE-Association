<?php

namespace App\Controllers;

class MainController {
    protected string $view; 
    protected $subPage;
    protected $data;
    protected string $viewType = 'front'; 
    
    public function render() { 
        $base_uri = explode('/public/',$_SERVER['REQUEST_URI']);
        
        $data = $this->data;
        require __DIR__ . '/../views/' . $this->viewType . '/layouts/header.phtml';
        require __DIR__ . '/../views/' . $this->viewType . '/partials/' . $this->view . '.phtml';
        require __DIR__ . '/../views/' . $this->viewType . '/layouts/footer.phtml';
    }

    protected function checkUserAuthorization(int $role)
    {
        if (isset($_SESSION['user_id'])) {
            $currentUserRole = $_SESSION['user_role'];
            // Si le rôle est inférieur ou égal au role demandé (le rôle 1 est le plus haut)
            if ($currentUserRole <= $role) {
                return true;
                
            }else if($currentUserRole == 3){
                $redirect = explode('/public/', $_SERVER['REQUEST_URI']);
            header('Location: ' . $redirect[0] . '/public/profile');
               
               
            }else {
                http_response_code(403);
                $this->view = '403';
                $this->render();
                exit();
            }
        } else  {
            $redirect = explode('/public/', $_SERVER['REQUEST_URI']);
            header('Location: ' . $redirect[0] . '/public/login');
            exit();
        }
    }
    
    public function getView(): string 
    { // un getter retourne tjs la valeur de la propriete
        return $this->view;
    }
    
    public function setView( string $View): void 
    { // permet de mod la vue
        $this->view = $View  ;
    }
    
    public function getSubPage(): string 
    {
        return $this->subPage;
    }
    
    public function setSubPage(?string $value): void 
    {
        $this->subPage = $value;
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