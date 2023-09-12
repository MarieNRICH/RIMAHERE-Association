<?php
namespace App\Models;
use App\Utility\DataBase;
use \PDO;


Class ActivityModel {
    // representation de tes tables en code : 
    
    private $id;
    private $name;
    private $date;
    private $description;
    private $img;
    private $user_id;
    
    public function getId() { // un getter récupérer une donnée et retourne tjs la valeur de la propriete
        return $this->id; // $this, représent la class ActivityModel, 
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
    
    public function getDate() {
        return $this->date;
    }
    
    public function setDate($date) {
        $this->date = $date;
    }
    
    public function getContent() {
        return $this->description;
    }
    
    public function setContent($description) {
        $this->description = $description;
    }
    
    public function getImg() {
        return $this->img;
    }
    
    public function setImg($img) {
        $this->img = $img;
    }
    
    public function getUserId() {
        return $this->user_id;
    }
    
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }
    
    public static function getActivities(int $limit = null): ?array {
        $dsn = DataBase::connectPDO();
        // Commencer à construire la requête SQL
        $sql = 'SELECT * FROM activities ';
        
        // Si le paramètre "post-tri" existe et n'est pas vide
        if (isset($_GET['post-tri']) && !empty($_GET['post-tri'])) {
            // Ajouter à la requête l'ordonnancement
            $sql .= 'ORDER BY date ' . $_GET['post-tri'];
        }
        
        // Si le paramètre "nb-post" existe et n'est pas vide
        if (isset($_GET['nb-post']) && !empty($_GET['nb-post'])) {
            // Ajouter à la requête la limitation du nombre d'articles
            $sql .= ' LIMIT ' . $_GET['nb-post'];
        } elseif (!empty($limit)) {
            // Si le paramètre $limit est fourni à la fonction, utiliser cette limite
            $sql = "SELECT * FROM activities LIMIT " . $limit;
        }
        
        // Préparer et exécuter la requête
        $query = $dsn->prepare($sql);
        $query->execute();
        
        // Récupérer les articles sous forme d'un tableau d'objets de type ActivityModel
        $activites = $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\ActivityModel');
        
        // Retourner le tableau de $activities
        return $activites;
    } 
    
    public function getActivityById($id) 
    {
        $dsn = DataBase::connectPDO();
        // Utiliser des paramètres dans la requête pour éviter les failles d'injection SQL
        $sql = "SELECT * FROM activities WHERE id=:id";
        $param = ['id'=> $id];
        // Préparer et exécuter la requête
        $query = $dsn->prepare($sql);
        $query->execute($param);
        
        // Définir le mode de récupération en FETCH_CLASS
        $query->setFetchMode(PDO::FETCH_CLASS, 'App\Models\ActivityModel');
        
        // Récupérer le message (post)
        $post = $query->fetch();
        
        return $post;
        
    } 
}

?>