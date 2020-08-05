<?php

function checkDbState($connect, $dbName) {
  $db_exists = mysqli_select_db($connect,$dbName);
  if (!$db_exists) {
    createDataBase($connect, $dbName);
  }

  $users = mysqli_query($connect, "SELECT * FROM `users`");
  $books = mysqli_query($connect, "SELECT * FROM `books`");

  $tablesExists = (($users->num_rows > 0) && ($books->num_rows > 0));
  if($tablesExists) 
    return;
    
  if(empty($users)) {
    createTableUsers($connect);
  }

  if(empty($books)) {
    createTableBooks($connect);
  }

  $response = loadFromApi();
  $usersFromApi = $response["user"];
  $booksFromApi = $response["book"];
    
  if(empty($users) || $users->num_rows === 0) {
    addUsers($connect, $usersFromApi);
  }

  if(empty($books) || $books->num_rows === 0) {
    addBooks($connect, $booksFromApi);
  }
}

function createDataBase($connect,$dbName) {
  $query = "CREATE DATABASE ".$dbName;
  
  if (!mysqli_query($connect, $query)) {
    echo '<div class="alert alert-danger" role="alert">
    Ошибка при создании базы данных: '.mysqli_error() .'</div>';
    die();
  }
  mysqli_select_db($connect,$dbName);
}

function createTableUsers($connect) {
  $query = "CREATE TABLE users(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    uid INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    old INT NOT NULL
  )";

  if (!mysqli_query($connect, $query)) {
    echo '<div class="alert alert-danger" role="alert">
    Ошибка при создании таблицы USERS: '.mysqli_error() .'</div>';
    die();
  }
}

function createTableBooks($connect) {
  $query = "CREATE TABLE books(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    uid INT 
  )";

  if (!mysqli_query($connect, $query)) {
    echo '<div class="alert alert-danger" role="alert">
    Ошибка при создании таблицы BOOKS: '.mysqli_error() .'</div>';
    die();
  }
}

function loadFromApi() {
  $url = 'http://z.bokus.ru/user.json';
  $response = file_get_contents($url);
  if(!$response) {
    echo '<div class="alert alert-danger" role="alert">
    Ошибка получения данных с URL: '.$url.'</div>';
    die();
  }
  echo '<div class="alert alert-warning" role="alert">
    Данные с URL: '.$url.' успешно получены </div>';
  return json_decode($response, true);
}

function toArray($data) {
  $arr = [];
  while($row = mysqli_fetch_assoc($data)) {
    $arr[] = $row;
  }
  return $arr;
}

function getTableData($connect) {
  $query = "SELECT * FROM `books` LEFT JOIN `users` ON `users`.`uid` = `books`.`uid`";
  $response = mysqli_query($connect, $query);
  $data = toArray($response);
  return $data;
}

function addUsers($connect, $data) { 
  foreach($data as $row)
  {
    $name = $row['name'];
    $old = $row['old'];
    $uid = $row["uid"];
    $query = "INSERT INTO `users` (`id`,`uid`, `name`, `old`) VALUES (NULL,'".$uid."', '".$name."', '".$old."')";
    mysqli_query($connect, $query);
  }
}

function addBooks($connect, $data) {
  foreach($data as $row)
  {
    $title = $row["title"];
    $year = $row["year"];
    if(array_key_exists('uid', $row)){
      $uid = $row["uid"];
      $query = "INSERT INTO `books`(`id`, `title`, `year`, `uid`) VALUES (NULL,'".$title."','".$year."','".$uid."')";
    } else {
      $uid = NULL;
      $query = "INSERT INTO `books`(`id`, `title`, `year`, `uid`) VALUES (NULL,'".$title."','".$year."', NULL)";
    }
    mysqli_query($connect, $query);
  }
}