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
    
    public function getNivel() {
        return $this->nivel;
    }
    
    public function setNivel($nivel) {
        $this->nivel = $nivel;
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
        $activities = $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\ActivityModel');
        
        // Retourner le tableau de $activities
        return $activities;
    } 
    
    public function getActivityById($id)
    {
        $dsn = DataBase::connectPDO();
    
        // Utiliser un paramètre lié pour sécuriser la requête
        $sql = "SELECT users.* FROM users
                INNER JOIN activity_has_user ON users.user_id = activity_has_user.user_id
                WHERE activity_has_user.activity_id = :id";
    
        // Préparer et exécuter la requête avec un paramètre lié
        $query = $dsn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    
        // Récupérer la liste des utilisateurs
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        var_dump($users);
        return $users;
    }
    
    // méthode pour enregistrer une activité en bdd
    public function addActivity(): bool 
    {
        $dsn = DataBase::connectPDO();
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO `activities`(`date`, `name`,`description`,`nivel`) VALUES (:date,:name,:description,:nivel)";
        $param = [
            'date'=> $this->date,
            'name' => $this->name,
            'description' => $this->description,
            'nivel' => $this->nivel,
            ];
        // Préparer et exécuter la requête
        $query = $dsn->prepare($sql);
        $query->execute($param);
        
        // Définir le mode de récupération en FETCH_CLASS
        $query->setFetchMode(PDO::FETCH_CLASS, 'App\Models\ActivityModel');
        
        // Récupérer le message (post)
        $post = $query->fetch();
        
        return $post;
        
    } 
    
     public function updateActivity(): bool
    {

        $pdo = DataBase::connectPDO();

        // création requête avec liaison de param pour éviter les injections sq
        $sql = "UPDATE `activities` SET `id` (`date`, `name`,`description`,`nivel`) VALUES (:date,:name,:description,:nivel) WHERE 1";
        // préparation de la requête
        $pdoStatement = $pdo->prepare($sql);
        // liaison des params avec leur valeurs. tableau à passer dans execute
        $params = [
            ':date' => $this->date,
            ':name' => $this->name,
            ':description' => $this->description,
            ':nivel' => $this->nivel,
        ];
        // récupération de l'état de la requête (renvoie true ou false)
        $queryStatus = $pdoStatement->execute($params);

        // on retourne le status
        return $queryStatus;
    }
    
    public function removeActivity(): void
    {
        $pdo = DataBase::connectPDO();

        // création requête avec liaison de param pour éviter les injections sq
        $sql = "DELETE FROM `activities` SET `id` (`date`, `name`,`description`,`nivel`) VALUES (:date,:name,:description,:nivel) WHERE 0";
        // préparation de la requête
        $pdoStatement = $pdo->prepare($sql);
        // liaison des params avec leur valeurs. tableau à passer dans execute
        $params = [
            ':date' => $this->date,
            ':name' => $this->name,
            ':description' => $this->description,
            ':nivel' => $this->nivel,
        ];
        
        // récupération et filtrage du champs 
        $activityId = filter_input(INPUT_POST, 'activityid', FILTER_SANITIZE_SPECIAL_CHARS);

        if (ActivityModel::deleteActivity($activityId)) {
            $this->data['infos'] = '<div class="alert alert-success d-inline-block mx-4" role="alert">Activité supprimé avec succès</div>';
        } else {
            $this->data['infos'] = '<div class="alert alert-danger" role="alert">Il s\'est produit une erreur</div>';
        }
    }
}

?>