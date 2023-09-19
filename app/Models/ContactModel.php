<?php
namespace App\Models;
use App\Utility\DataBase;
use \PDO;

// Ce modèle est la représentation "code" de notre table posts
// elle aura donc autant de propriétés qu'il y'a de champs dans la table
// ça nous permettra de manipuler des objets identiques à une entrée de bdd grâce à PDO::FETCH_CLASS
class ContactModel
{
    private $id;
    private $name;
    private $email;
    private $message;
    private $rgpdChecked;

    // méthode pour enregistrer un Contact en bdd
    public function registerContact(): bool
    {

        $pdo = DataBase::connectPDO();

        // création requête avec liaison de param pour éviter les injections sq
        $sql = "INSERT INTO `contact`(`name`, `email`,`message`,`rgpdChecked`) VALUES (:name,:email,:message,:rgpdChecked)";
        // préparation de la requête
        $pdoStatement = $pdo->prepare($sql);
        // liaison des params avec leur valeurs. tableau à passer dans execute
        $params = [
            ':name' => $this->name,
            ':email' => $this->email,
            ':message' => $this->message,
            ':rgpdChecked' => $this ->rgpdChecked,
            
        ];
        // récupération de l'état de la requête (renvoie true ou false)
        $queryStatus = $pdoStatement->execute($params);
        // var_dump($queryStatus);
        
        return $queryStatus;
        
    }
    
    public function getMessages(){
        $pdo = DataBase::connectPDO();
        $sql = "SELECT * FROM contact";

        $query = $pdo->prepare($sql);
        $query->execute();
        $messages = $query->fetchAll(PDO::FETCH_CLASS,'App\Models\ContactModel');
        return $messages;
    }
    
    public function getId() { // un getter récupérer une donnée et retourne tjs la valeur de la propriete
        return $this->id; // $this, représent la class ContactModel, 
    }
    
    public function setId($id) { // permet de mod la vue
        $this->id = $id  ; // this id devient id
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function getRgpdChecked()
    {
        return $this->rgpdChecked;
    }
    
    public function setRgpdChecked($rgpdChecked) : void
    {
        $this->rgpdChecked = $rgpdChecked;
    }
}