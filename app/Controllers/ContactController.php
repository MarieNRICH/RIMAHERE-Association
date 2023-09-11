<?php 

namespace App\Controllers;
use App\Models\ContactModel;

class ContactController extends MainController{

 public function renderContact(): void
    {
        var_dump($_POST);
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
        
        $errors = [];
        //filter input permet de faire le if isset sans faire pleins de conditions
        $email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, 'message',FILTER_SANITIZE_SPECIAL_CHARS);        
        $name = filter_input(INPUT_POST, 'name',FILTER_SANITIZE_SPECIAL_CHARS);   
        $rgpdChecked = filter_input(INPUT_POST, 'rgpdChecked', FILTER_SANITIZE_SPECIAL_CHARS);
  
        // Si un champs vaut false, on ajoute une erreur dans le tableau errors
        if (!$email || !$message || !$name || !$rgpdChecked)  {
            
            $errors[] = '<div class="alert alert-danger" role="alert">Tous les champs sont obligatoires</div>';
        }
        // filter_var permet de vérifier si la valeur correspond bien au pattern attendu par se champs
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);        
        if ($email === false) {            
            $errors[] = '<div class="alert alert-danger" role="alert">Le format de l\'email n\'est pas valide.</div>';
        }
        if (strlen($message) > 1000) {
            $errors[] = '<div class="alert alert-danger" role="alert">Le message ne doit contenir plus de 1000 caractères.</div>';
        }
        if(!empty($errors)){   
            $this->data['errors'] = $errors;       
        }else{            
            // Création de l'objet contact
            $contact = new ContactModel();            
            // Remplissage des propriétés via les setters
            $contact->setName($name);
            $contact->setEmail($email);
            $contact->setMessage($message); 
            $contact->setRgpdChecked($rgpdChecked);
            
            
            if (!$rgpdChecked == 'on')
            {
                
               $this->data['errors'] ='<div class="alert alert-danger" role="alert">Veuillez confirmer votre consentement à notre règlementation RGPD.</div>';
            }
           else {
                if($contact->registerContact()){                   
                    $this->data['success'] =  '<div class="alert alert-success" role="alert">Enregistrement réussi</div>';                
                }else{
                    $this->data['errors'] = '<div class="alert alert-danger" role="alert">Il y a eu une erreur lors de l\enregistrement</div>';
                } 
            }
        }
            
            // on redirige l'utilisateur sur la page moncompte                
            // header('Location:'.$_SERVER['REQUEST_URI'].'/../');                        
        }
    }
