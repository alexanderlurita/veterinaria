<?php

require_once 'Conexion.php';

class Mascota extends Conexion
{
  private $connection;

  public function __CONSTRUCT()
  {
    $this->connection = parent::getConexion();
  }

  public function create($data = [])
  {
    try {
      $query = $this->connection->prepare("CALL spu_mascotas_registrar(?,?,?,?,?,?)");
      $query->execute(
        array(
          $data['idcliente'],
          $data['idraza'],
          $data['nombre'],
          $data['fotografia'],
          $data['color'],
          $data['genero']
        )
      );
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function listPetsByOwner($dniCliente)
  {
    try {
      $query = $this->connection->prepare("CALL spu_mascotas_buscar_cliente(?)");
      $query->execute(
        array(
          $dniCliente
        )
      );

      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function searchPet($idmascota)
  {
    try {
      $query = $this->connection->prepare("CALL spu_mascotas_buscar(?)");
      $query->execute(
        array(
          $idmascota
        )
      );

      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function listRaces()
  {
    try {
      $query = $this->connection->prepare("CALL spu_razas_listar()");
      $query->execute();
      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }
}
