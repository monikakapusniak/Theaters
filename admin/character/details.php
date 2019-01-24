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

  $query = "select character_name, spectacle_name from Characters NATURAL JOIN Spectacle WHERE id_character='$id'";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database address fetch fail.";
    exit();
  }
  $row['character_name'] = htmlspecialchars($row['character_name']);
  $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
?>

<div class="container">
  <a href="/admin/character/"><button class='btn btn-info'>Powrót do listy postaci</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Postać:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa postaci:</strong></td>
                <td><?php echo $row['character_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Spektakl:</strong></td>
                <td><?php echo $row['spectacle_name']; ?></td>
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