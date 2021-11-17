<?php

namespace App;

use App\Controller\ErrorController;

class Router extends \AltoRouter{

  /**
   *
   * Ajoute une route à faire correspondre en méthode GET
   *
   * @param string $route la route à faire correspondre (peut prendre la forme d'une regex). On peut utiliser des filtres comme [i:id] cf. doc AltoRouter
   * @param mixed $target la chose à faire lorsqu'une route trouve une correspondance
   * @param string $name le nom à donner à la route
   *
   */
  public function get($route, $target, $name = null) {
    $this->map('GET', $route, $target, $name);
    $this->map('GET', $route . '/', $target, $name);
    return $this;
  }

  /**
   *
   * Ajoute une route à faire correspondre en méthode POST
   *
   * @param string $route la route à faire correspondre (peut prendre la forme d'une regex). On peut utiliser des filtres comme [i:id] cf. doc AltoRouter
   * @param mixed $target la chose à faire lorsqu'une route trouve une correspondance
   * @param string $name le nom à donner à la route
   *
   */
  public function post($route, $target, $name = null) {
    $this->map('POST', $route, $target, $name);
    $this->map('POST', $route . '/', $target, $name);
    return $this;
  }

  /**
   *
   * Ajoute une route à faire correspondre en méthode DELETE
   *
   * @param string $route la route à faire correspondre (peut prendre la forme d'une regex). On peut utiliser des filtres comme [i:id] cf. doc AltoRouter
   * @param mixed $target la chose à faire lorsqu'une route trouve une correspondance
   * @param string $name le nom à donner à la route
   *
   */
  public function delete($route, $target, $name = null) {
    $this->map('DELETE', $route, $target, $name);
    $this->map('DELETE', $route . '/', $target, $name);
    return $this;
  }

  public function start() {
    $match = $this->match();

    if (is_array($match)) {
      $this->protectAdminRoutes();

      $target = $match['target'];
      $params = $match['params'];

      [$controller, $method] = explode('#', $target);

      $controller = "App\Controller\\$controller";
      $obj = new $controller();

      if (is_callable([$obj, $method])) {
        if (!empty($params)) {

          $obj->$method(...array_values($params));
          return;
        }

        $obj->$method();
        return;
      }
    }

    $errorContoller = new ErrorController();
    $errorContoller->get404();
  }

  private function getActiveURL() {
    return $_SERVER['REQUEST_URI'];
  }

  private function protectAdminRoutes() {
    $url = $this->getActiveURL();

    $user = $_SESSION['user'] ?? null;
    $user = unserialize($user) ?? null;

    if (str_contains($url, 'admin') && (!$user || $user->getUserType() !== "admin")) {
      header('Location: /');
    }
  }
}