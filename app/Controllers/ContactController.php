<?php 

namespace App\Controllers;
use App\Models\ContactModel;

class ContactController extends MainController{

public function renderContact(): void
    {
        // var_dump($_POST);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["contactForm"])) {
                $this->contact();
            } 
        }
        
        $this->render();
    }
public function contact()
    {
        // var_dump('HEHO !!');
        $errors = 0;
        $this->data['contactInfos'] = [];
        //filter input permet de faire le if isset sans faire pleins de conditions
        $name = filter_input(INPUT_POST, 'name',FILTER_SANITIZE_SPECIAL_CHARS);   
        $email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, 'message',FILTER_SANITIZE_SPECIAL_CHARS);        
        $rgpdChecked = filter_input(INPUT_POST, 'rgpdChecked', FILTER_SANITIZE_SPECIAL_CHARS);
  
        // Si un champs vaut false, on ajoute une erreur dans le tableau errors
        if (!$name || !$email || !$message || !$rgpdChecked)  {
            $errors = 1;
            array_push($this->data ['contactInfos'],'<div class="error">Tous les champs sont obligatoires</div>');
        }
        // filter_var permet de vérifier si la valeur correspond bien au pattern attendu par se champs
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);        
        if ($email === false) {    
            $errors = 1;
            array_push($this->data['contactInfos'],'<div class="error">Le format de l\'email n\'est pas valide.</div>');
        }
        if (strlen($message) >= 250) {
            $errors = 1;
            array_push($this->data['contactInfos'],'<div class="error">Le message ne doit contenir plus de 250 caractères.</div>');
        }
        else{            
            $contact = new ContactModel();
            $contact->setName($name);
            $contact->setEmail($email);
            $contact->setMessage($message); 
            $contact->setRgpdChecked($rgpdChecked);
            
            
        if (!$rgpdChecked == 'on'){
            $errors = 1;
            array_push($this->data['contactInfos'],'<div class="error">Veuillez confirmer votre consentement à notre règlementation RGPD.</div>');
            } 
            else{
                
            if($contact->registerContact())
                {                   
                $errors = 1;
                array_push($this->data['contactInfos'],'<div class="success">Votre message a bien été envoyé</div>');
                }
                else{
                $errors = 1;
                array_push($this->data['contactInfos'], '<div class="error">Il y a eu une erreur lors de l\enregistrement</div>');
                } 
            }
        }
    }
}