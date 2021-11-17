<?php

namespace App\Controller;

class MainController extends Controller {
  public function getHome() {
    $this->render('home.twig');
  }

  public function getArticles() {
    $this->render('articles.twig');
  }
}