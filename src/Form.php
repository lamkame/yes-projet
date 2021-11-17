<?php

namespace App;

use Ramsey\Uuid\Uuid;

class Form {
  /**
   *
   * Cette méthode permet de valider le formulaire en vérifiant si des champs sont vides, que l'email est bien un email, etc...
   *
   * @param array $data tableau contenant les données du formulaire
   * @param array $optionnalFields un tableau qui represente les champs optionnels d'un formulaire
   * @param bool $loginForm paramètre permettant de savoir s'il s'agit d'un formulaire de connexion
   *
   * @return array Un tableau contenant les potentielles erreurs du formulaire
   *
   */
  public static function validate(array $data, array $optionnalFields = [], bool $loginForm = false): array{
    $errors = [];

    // on boulce sur le tableau data pour recuperer les données 1 par 1
    foreach ($data as $key => $value) {
      // si le champs est vide ET que le champs n'est pas compris dans le tableau de champs optionnels
      if (!$value && !in_array($key, $optionnalFields)) {
        $errors[$key] = 'Ce champs ne peut pas être vide.';
        // on passe au tour de boucle suivant donc le code qui se trouve en dessous de continue ne sera pas executé pour ce tour de boucle
        continue;
      }

      if (!$loginForm) {
        // str_contains permet de vérifier si une chaine inclus une autre chaine
        if (str_contains($key, 'email') && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $errors[$key] = "Cet email n'est pas un email valide.";
        }

        // on vérifie que le mot de passe contient au minimum une majuscule, une miniscule, une lettre, un chiffre, un symbole
        if (str_contains($key, 'password') && !preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-\/+_]).{8,}$/', $value)) {
          $errors[$key] = 'Votre mot de passe est trop faible.';
        }
      }
    }

    return $errors;
  }

  /**
   *
   * Cette méthode permet d'envoyer un fichier au serveur et de le stocker
   *
   * @param string $name Nom à aller chercher dans le tableau $_FILES
   * @param string $uploadDir Le chemin vers le fichier à partir de la racine du projet
   * @param array &$errors Le tableau d'erreur à modifier en cas d'erreur fichier
   * @param array $options Un tableau d'options avec la clé allowedExtensions contenant un tableau correspondant aux fichiers acceptés
   *
   * @return array Le(s) chemin(s) d'ajout du/des fichier(s) à stocker en base de donnée
   *
   */
  public static function uploadFile(string $name, string $uploadDir, array &$errors = [], array $options = []) {
    $allowedTypes = $options['allowedTypes'] ?? null;
    $absolutePath = dirname(__DIR__) . $uploadDir . '/';

    if (!is_dir($absolutePath)) {
      mkdir($absolutePath, 0755, true);
    }

    $images = $_FILES[$name] ?? null;

    $imagePaths = null;

    if ($images) {
      for ($i = 0; $i < sizeof($images); $i++) {
        if ($images['error'][$i] === UPLOAD_ERR_OK) {
          ['extension' => $extension, 'filename' => $filename] = pathinfo($images['name'][$i]);

          if ($allowedTypes && !in_array($extension, $allowedTypes)) {
            $errors['fileError'] = "Type de fichier non accepté.";
          }

          if (empty($errors) && $filename) {
            $filename = hash('sha256', Uuid::uuid4() . $filename);
            $filename .= ".$extension";

            $uploadFile = $uploadDir . '/' . $filename;

            if (!move_uploaded_file($images['tmp_name'][$i], dirname(__DIR__) . $uploadFile)) {
              $errors['fileError'] = "Problème durant l'ajout du fichier.";
              continue;
            }

            if (!$imagePaths) {
              $imagePaths = [];
            }

            array_push($imagePaths, $uploadFile);

          }

        }
      }
    }

    return $imagePaths;
  }
}
}