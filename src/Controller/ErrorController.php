<?php

namespace App\Controller;

class ErrorController extends Controller {
  public function get404() {
    // on envoie un header avec le code d'erreur 404 not found
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    $this->render('404.twig');
  }

  public function get500() {
    // on envoie un header avec le code d'erreur 500 internal server error
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $this->render('500.twig');
  }
}