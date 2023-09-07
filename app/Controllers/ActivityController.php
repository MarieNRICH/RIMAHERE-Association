<?php

namespace App\Controllers;

use App\Controllers\MainController;
use App\Models\ActivityModel;

class ActivityController extends MainController
{

    public function renderActivity(): void
    {
        // on alimente la propriété data avec l'article 
        $this->data =  ActivityModel::getActivities();
        // on construit la page
        $this->render();
    }
    
}