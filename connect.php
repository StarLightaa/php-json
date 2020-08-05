<?php

$servername = "localhost";
$username = "root";
$password = "123";
$dbName = "bokus";

$connect = @mysqli_connect(
  $servername,
  $username,
  $password,
);

if (!$connect) {
  echo '<div class="alert alert-danger" role="alert">
    Ошибка при подключении к MySql: '.mysqli_connect_error().'</div>';
  die();
}