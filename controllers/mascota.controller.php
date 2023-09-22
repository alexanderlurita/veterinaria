<?php

require_once '../models/Mascota.php';

$mascota = new Mascota();

if (isset($_POST['operacion'])) {

  if ($_POST['operacion'] == 'add') {
    $uploadDirectory = "";
    $uploadedFileName = "";
    $uploadFilePath = "";

    if ($_POST['fotografia'] != "") {
      $uploadDirectory = "../images/uploads/";
      $uploadedFileName = sha1(date('c')) . '.jpg';
      $uploadFilePath = $uploadDirectory . $uploadedFileName;

      file_put_contents($uploadFilePath, base64_decode($_POST['fotografia']));
    }

    $data = [
      "idcliente"   => $_POST['idcliente'],
      "idraza"      => $_POST['idraza'],
      "nombre"      => $_POST['nombre'],
      "fotografia"  => $uploadedFileName,
      "color"       => $_POST['color'],
      "genero"      => $_POST['genero']
    ];
    $mascota->create($data);
  }
}

if (isset($_GET['operacion'])) {

  if ($_GET['operacion'] == 'listPetsByOwner') {
    $result = $mascota->listPetsByOwner($_GET['dni']);

    echo json_encode($result);
  }

  if ($_GET['operacion'] == 'search') {
    $result = $mascota->searchPet($_GET['idmascota']);
    echo json_encode($result);
  }

  if ($_GET['operacion'] == 'listRaces') {
    $result = $mascota->listRaces();
    echo json_encode($result);
  }
}
