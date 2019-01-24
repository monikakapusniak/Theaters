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

  $query = "select id_address, street, number, city, postal_code, id_city from Address NATURAL JOIN City WHERE id_address='$id'";
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

  $row['id_address'] = htmlspecialchars($row['id_address']);
  $row['street'] = htmlspecialchars($row['street']);
  $row['number'] = htmlspecialchars($row['number']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['postal_code'] = htmlspecialchars($row['postal_code']);
?>

<div class="container">
  <a href="/admin/address/"><button class='btn btn-info'>Powrót do listy adresów</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Adres:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
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