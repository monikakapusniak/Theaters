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

  $query = "select id_show, spectacle_name, show_date, hall_name, id_spectacle, id_hall from Shows NATURAL JOIN Spectacle NATURAL JOIN Hall WHERE id_show='$id'";
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
  $row['show_date'] = htmlspecialchars($row['show_date']);
  $row['hall_name'] = htmlspecialchars($row['hall_name']);
?>

<div class="container">
  <a href="/admin/shows/"><button class='btn btn-info'>Powrót do listy występów</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Występ:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Spektakl:</strong></td>
                <td><?php echo $row['spectacle_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Data:</strong></td>
                <td><?php echo $row['show_date']; ?></td>
              </tr> 
              <tr>
                <td><strong>Sala:</strong></td>
                <td><?php echo $row['hall_name']; ?></td>
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