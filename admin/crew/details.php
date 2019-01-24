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

  $query = "select kind, CONCAT(firstname, ' ', lastname) as name, concat(spectacle_name, ' ', convertDatetimeToDateString(show_date)) as showdate, id_crew from Crew c NATURAL JOIN Crew_type natural join Person Left join Shows sh on sh.id_show = c.id_show left join Spectacle sp on sp.id_spectacle = sh.id_spectacle where id_crew='$id';";

  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database crew fetch fail.";
    exit();
  }
  $row['kind'] = htmlspecialchars($row['kind']);
  $row['name'] = htmlspecialchars($row['name']);
  $row['showdate'] = htmlspecialchars($row['showdate']);
?>

<div class="container">
  <a href="/admin/crew/"><button class='btn btn-info'>Powrót do listy osób związanych ze spektaklem.</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Osoba związana z spektaklem:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Imię i nazwisko:</strong></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
              </tr>
              <tr>
                <td><strong>Typ roli:</strong></td>
                <td><?php echo htmlspecialchars($row['kind']); ?></td>
              </tr>  
              <tr>
                <td><strong>Spektakl:</strong></td>
                <td><?php echo htmlspecialchars($row['showdate']); ?></td>
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