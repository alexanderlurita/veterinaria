<?php

require_once 'Conexion.php';

class Cliente extends Conexion
{
  private $connection;

  public function __CONSTRUCT()
  {
    $this->connection = parent::getConexion();
  }

  public function create($data = [])
  {
    try {
      $query = $this->connection->prepare("CALL spu_clientes_registrar(?,?,?,?)");
      $query->execute(
        array(
          $data['apellidos'],
          $data['nombres'],
          $data['dni'],
          $data['claveacceso']
        )
      );
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function login($username)
  {
    try {
      $query = $this->connection->prepare("CALL spu_clientes_iniciar_sesion(?)");
      $query->execute(
        array($username)
      );
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }
}
