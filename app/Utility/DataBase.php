<?php
namespace App\Utility;
use \PDO;

class DataBase // conx à la bdd
{    
    private $dsn;
    private static $_instance;

    private function __construct()
    {   
        //config bdd frm file ini qui evite la transmission d'info sensible lors d'un push ou partage
        $configData = parse_ini_file(__DIR__ . '/../config.ini'); 

        try {
            // permet d'établir la connexion à la base de données
            $this->dsn = new PDO(
                "mysql:host={$configData['DB_HOST']};dbname={$configData['DB_NAME']};charset=utf8",
                $configData['DB_USERNAME'],
                $configData['DB_PASSWORD']                
            );
        } catch (\Exception $exception) {            
            // En cas d'échec de connexion, affichage de l'erreur et arrêt du script
            echo $exception->getMessage() . '<br>';                                    
            die;
        }
    }    

    // Méthode statique pour obtenir une instance unique de connexion PDO
    public static function connectPDO()
    {        
        // Si aucune instance n'a été créée, en créer une
        // ici on utilise self pour faire référence à la classe car
        // comme c'est une méthode statique, $this ne sera pas accessible
        if (empty(self::$_instance)) {
            // on remarque que cette méthode instancie sa propre classe
            self::$_instance = new Database();
        }
        // Retourner l'instance de connexion PDO
        return self::$_instance->dsn;
    }
}