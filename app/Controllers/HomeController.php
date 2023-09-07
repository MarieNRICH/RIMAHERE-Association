<?php

namespace App\Controllers;
use App\Models\PostModel;

class HomeController extends MainController {
    
    public function renderHome(){
        $postModel = new PostModel(); // il transmet les data au MainController.
        $this->data = $postModel->getPosts(4); //puis il appel le render de MainController pr construire la page.
        $this->render();
        
    }
}
?>