<?php

require_once '../models/Cliente.php';
require_once '../utils/Crypter.php';

$cliente = new Cliente();

if (isset($_POST['operacion'])) {

  if ($_POST['operacion'] == 'add') {
    $claveEncriptada = encrypt($_POST['claveacceso']);

    $data = [
      "apellidos"     => $_POST['apellidos'],
      "nombres"       => $_POST['nombres'],
      "dni"           => $_POST['dni'],
      "claveacceso"   => $claveEncriptada
    ];
    $cliente->create($data);
  }
}
