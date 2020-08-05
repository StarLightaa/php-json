<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Автор</th>
      <th scope="col">Возраст</th>
      <th scope="col">Название</th>
      <th scope="col">Год</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($data as $key=>$row) :?>
      <tr>
        <th scope="row"><?php echo $key+1; ?></th>
        <td><?php echo $row["name"]; ?></td>
        <td><?php echo $row["old"]; ?></td>
        <td><?php echo $row["title"]; ?></td>
        <td><?php echo $row["year"]; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>