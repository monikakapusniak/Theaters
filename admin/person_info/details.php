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

  $query = "select description, concat(firstname, ' ', lastname) as name, convertDateTimeToDateString(date_of_info) as date_of_info from Person_info natural join Person where id_person_info='$id';";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database person info fetch fail.";
    exit();
  }  
  $row['description'] = htmlspecialchars($row['description']);
  $row['name'] = htmlspecialchars($row['name']);
  $row['date_of_info'] = htmlspecialchars($row['date_of_info']);
?>

<div class="container">
  <a href="/admin/person_info/"><button class='btn btn-info'>Powrót do listy informacji o osobach</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Informacje o osobie:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Imię i nazwisko:</strong></td>
                <td><?php echo $row['name']; ?></td>
              </tr>     
              <tr>
                <td><strong>Opis:</strong></td>
                <td><?php echo $row['description']; ?></td>
              </tr>  
              <tr>
                <td><strong>Data:</strong></td>
                <td><?php echo $row['date_of_info']; ?></td>
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