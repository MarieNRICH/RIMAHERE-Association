<?php
namespace App\Models;
use App\Utility\DataBase;

// Ce modèle est la représentation "code" de notre table posts
// elle aura donc autant de propriétés qu'il y'a de champs dans la table
// ça nous permettra de manipuler des objets identiques à une entrée de bdd grâce à PDO::FETCH_CLASS
class UserModel
{
    private $id;
    private $name;
    private $firstname;
    private $email;
    private $phone;
    private $password;
    private $role;

    // méthode pour enregistrer un user en bdd
    public function registerUser(): bool
    {

        $pdo = DataBase::connectPDO();

        // création requête avec liaison de param pour éviter les injections sq
        $sql = "INSERT INTO `users`(`name`, `firstname`,`email`,`phone`,`password`,`role`) VALUES (:name,:firstname,:email,:phone,:password,:role)";
        // préparation de la requête
        $pdoStatement = $pdo->prepare($sql);
        // liaison des params avec leur valeurs. tableau à passer dans execute
        $params = [
            ':name' => $this->name,
            ':firstname' => $this->firstname,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':password' => $this->password,
            // par défaut on force le role à 3 qui est le plus faible
            ':role' => 3,
        ];
        // récupération de l'état de la requête (renvoie true ou false)
        $queryStatus = $pdoStatement->execute($params);

        // on retourne le status
        return $queryStatus;
    }

    public function checkEmail(): bool
    {
        $pdo = DataBase::connectPDO();

        // création requête avec liaison de param pour éviter les injections sq
        $sql = "SELECT COUNT(*) FROM `users` WHERE `email` = :email";
    
        $query = $pdo->prepare($sql);
    
        $query->bindParam(':email', $this->email);
    
        $query->execute();
        // on stock le retour. fetchColumn renvoie le nombre d'éléments trouvé
        $isMail = $query->fetchColumn();

        // donc l'instruction $isMail > 0 donnera true s'il y'a déjà l'email présent
        return $isMail > 0;
    }

    public static function getUserByEmail($email): ?UserModel
    {
        $pdo = DataBase::connectPDO();

        $sql = '
        SELECT * 
        FROM users
        WHERE email = :email';
        $pdoStatement = $pdo->prepare($sql);
    
        $pdoStatement->execute([':email' => $email]);
    
        $result = $pdoStatement->fetchObject('App\Models\UserModel');

        // si l'email ne correspond pas, ça va renvoyer false et on va rentrer dans la condition (car différent de true)        
        if(!$result){
            
            // on donne à result null car notre méthode doit renvoyer soit UserModel soit null
            $result = null;
        }
        // on renvoie le résultat
        return $result;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function getPhone(): int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): void
    {
        $this->phone = $phone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): void
    {
        $this->role = $role;
    }
}