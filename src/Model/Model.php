<?php

namespace App\Model;

class Model {
  protected $id;
  protected $created_at;
  protected $updated_at;

  public function getId() {
    return $this->id;
  }
  public function setId(int $id): self {
    $this->id = $id;
    return $this;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt(string $created_at) {
    $this->created_at = $created_at;
    return $this;
  }

  public function getUpdatedAt() {
    return $this->updated_at;
  }
  public function setUpdatedAt(string $updated_at) {
    $this->updated_at = $updated_at;
    return $this;
  }
}