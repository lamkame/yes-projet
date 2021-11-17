<?php

namespace App;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use App\Controller\ErrorController;

class Database {
  /**
   *
   * Cette méthode permet de charger les variables d'environnement et retourne une instance PDO servant à effectuer des requetes en base de donnée
   *
   * @return PDO retourn une instance de PDO
   *
   */
  public static function getConnection(): PDO | null{
    // on utilise le paquet phpdotenv pour charger les variables d'environement
    $dotenv = Dotenv::createImmutable(dirname(__DIR__)); // ici on point vers la racine du projet à l'endroit ou se trouve le fichier .env
    $dotenv->load();

    $pdo = null;

    try {
      $pdo = new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $e) {
      // si on a un problème durant la creation de la connexion on envoie une page 500
      $errorController = new ErrorController();
      $errorController->get500();
      return null;
    }

    if ($pdo) {
      return $pdo;
    }
  }
}