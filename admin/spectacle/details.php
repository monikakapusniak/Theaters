<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
  require('../../shared/header.php');
  require('../../shared/navbar.php');

  if($user_type != "Admin") {
      header("location: /shared/error.php");
  }

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../../database_connection.php';

  $query = "select spectacle_name, duration, convertDatetimetoDateString(date_of_premiere) as date_of_premiere, genre, description, photo_name, number_of_ratings, sum_of_ratings from Spectacle NATURAL JOIN Genre WHERE id_spectacle='$id'";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database show fetch fail.";
    exit();
  }
  $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
  $row['duration'] = htmlspecialchars($row['duration']);
  $row['date_of_premiere'] = htmlspecialchars($row['date_of_premiere']);
  $row['genre'] = htmlspecialchars($row['genre']);
  $row['description'] = htmlspecialchars($row['description']);
  $row['photo_name'] = htmlspecialchars($row['photo_name']);
  $row['number_of_ratings'] = htmlspecialchars($row['number_of_ratings']);
  $row['sum_of_ratings'] = htmlspecialchars($row['sum_of_ratings']);
?>

<div class="container">
  <a href="/admin/spectacle/"><button class='btn btn-info'>Powrót do listy spektakli</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Spektakl:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa spektakli:</strong></td>
                <td><?php echo $row['spectacle_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Czas trwania:</strong></td>
                <td><?php echo $row['duration']; ?></td>
              </tr> 
              <tr>
                <td><strong>Data premiery:</strong></td>
                <td><?php echo $row['date_of_premiere']; ?></td>
              </tr>
              <tr>
                <td><strong>Gatunek:</strong></td>
                <td><?php echo $row['genre']; ?></td>
              </tr>
              <tr>
                <td><strong>Opis:</strong></td>
                <td><?php echo $row['description']; ?></td>
              </tr>
              <tr>
                <td><strong>photo_name:</strong></td>
                <td><?php echo $row['photo_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Ilość głosów:</strong></td>
                <td><?php echo $row['number_of_ratings']; ?></td>
              </tr>
              <tr>
                <td><strong>Suma głosów:</strong></td>
                <td><?php echo $row['sum_of_ratings']; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>