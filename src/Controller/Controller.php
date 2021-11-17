<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller {
  /** @var Environment */
  private $twig;

  public function __construct() {
    // on met en place Twig
    // d'après la doc on doit instancier un FilesystemLoader qui permet de dire à twig ou se trouvent nos vues (templates)
    $loader = new FilesystemLoader(dirname(dirname(__DIR__)) . '/views');
    // on instancie la class Environment en lui passant le loader defini ci dessus et en lui passant un tableau d'option concernant le cache
    $twig = new Environment($loader, [
      'cache' => false/** mettre l'endroit ou stocker les templates deja chargé */,
    ]);

    $user = $_SESSION['user'] ?? null;

    // on ajoute l'utilisateur connecté en tant que variable globale pour toutes les vues chargés par Twig
    $twig->addGlobal("user", $user ? unserialize($user) : null);

    // on affecte twig à la propriété de notre class twig
    $this->twig = $twig;
  }

  /**
   *
   * Affiche un template twig
   *
   *  @param string $name chemin de la vue à charger à partir du dossier defini à l'instanciation de twig
   *  @param array $context un tableau associatif des paramètres/variables/valeurs à envoyer à la vue. Ex: ['title' => 'Accueil']
   *
   */
  protected function render(string $name, array $context = []) {
    echo $this->twig->render($name, $context);
  }
}