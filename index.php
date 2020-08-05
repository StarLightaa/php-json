<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
  <div class="container mt-5">
    <?php 
      require_once 'connect.php';
      require_once 'functions.php';
      checkDbState($connect, $dbName);
      $data = getTableData($connect);
      include __DIR__.'/table.php';
    ?>
  </div>
</body>
</html>