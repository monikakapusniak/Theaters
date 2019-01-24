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

  echo "<script>console.log( 'Object: " . $id . "' );</script>";

  $query = "select id_hall, hall_name, theater_name, capacity, id_theater from Hall NATURAL JOIN Theater WHERE id_hall='$id'";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database hall fetch fail.";
    exit();
  }
  $row['hall_name'] = htmlspecialchars($row['hall_name']);
  $row['theater_name'] = htmlspecialchars($row['theater_name']);
  $row['capacity'] = htmlspecialchars($row['capacity']);
?>

<div class="container">
  <a href="/admin/hall/"><button class='btn btn-info'>Powrót do listy sal</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Sala:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa sali:</strong></td>
                <td><?php echo $row['hall_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Teatr:</strong></td>
                <td><?php echo $row['theater_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Pojemność:</strong></td>
                <td><?php echo $row['capacity']; ?></td>
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