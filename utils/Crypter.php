<?php

function encrypt($password = '')
{
  $passwordCrypt = password_hash($password, PASSWORD_BCRYPT);
  return $passwordCrypt;
}
