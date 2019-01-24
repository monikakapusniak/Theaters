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

  $query = "select character_name, CONCAT(firstname, ' ', lastname) as name, role_type, concat(spectacle_name, ' ', convertDatetimeToDateString(show_date)) as showdate from Casts c NATURAL JOIN Characters natural join Person Left join Shows sh on sh.id_show = c.id_show left join Spectacle sp on sp.id_spectacle = sh.id_spectacle natural join Role_type where id_cast='$id';";

  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database cast fetch fail.";
    exit();
  }
  $row['character_name'] = htmlspecialchars($row['character_name']);
  $row['name'] = htmlspecialchars($row['name']);
  $row['role_type'] = htmlspecialchars($row['role_type']);
  $row['showdate'] = htmlspecialchars($row['showdate']);
?>

<div class="container">
  <a href="/admin/cast/"><button class='btn btn-info'>Powrót do listy obsady</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Obsada:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa postaci:</strong></td>
                <td><?php echo $row['character_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Osoba:</strong></td>
                <td><?php echo $row['name']; ?></td>
              </tr> 
              <tr>
                <td><strong>Typ roli:</strong></td>
                <td><?php echo $row['role_type']; ?></td>
              </tr>  
              <tr>
                <td><strong>Spektakl:</strong></td>
                <td><?php echo $row['showdate']; ?></td>
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