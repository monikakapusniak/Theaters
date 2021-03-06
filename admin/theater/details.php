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

  $query = "select Theater.id_theater, Theater.theater_name, Address.street, Address.number, City.city, Address.postal_code from Theater JOIN (Address, City) on (Theater.id_address=Address.id_address and Address.id_city=City.id_city) WHERE id_theater='$id'";
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
  $row['theater_name'] = htmlspecialchars($row['theater_name']);
  $row['street'] = htmlspecialchars($row['street']);
  $row['number'] = htmlspecialchars($row['number']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['postal_code'] = htmlspecialchars($row['postal_code']);
?>

<div class="container">
  <a href="/admin/theater/"><button class='btn btn-info'>Powrót do listy teatrów</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Informacje o teatrze:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa teatru:</strong></td>
                <td><?php echo $row['theater_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Ulica:</strong></td>
                <td><?php echo $row['street']; ?></td>
              </tr>
              <tr>
                <td><strong>Numer domu/mieszkania:</strong></td>
                <td><?php echo $row['number']; ?></td>
              </tr>
              <tr>
                <td><strong>Miasto:</strong></td>
                <td><?php echo $row['city']; ?></td>
              </tr>
              <tr>
                <td><strong>Kod pocztowy:</strong></td>
                <td><?php echo $row['postal_code']; ?></td>
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