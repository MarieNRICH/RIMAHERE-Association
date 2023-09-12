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
}