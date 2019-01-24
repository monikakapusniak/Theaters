<?php 
  require('../shared/header.php');
  require('../shared/navbar.php');

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../database_connection.php';

  $query = 
  "select theater_name, createUrlPathToImg(photo_name) as photo_name, street, number, city, postal_code from Theater NATURAL JOIN Address NATURAL JOIN City where id_theater='$id';";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database theater fetch fail.";
    exit();
  }
  $row['theater_name'] = htmlspecialchars($row['theater_name']);
  $row['photo_name'] = htmlspecialchars($row['photo_name']);
  $row['street'] = htmlspecialchars($row['street']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['postal_code'] = htmlspecialchars($row['postal_code']);

?>
<div class="container">  
  <a href="/theater"><button class='btn btn-info'>Powrót do listy teatrów</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-3" align="center"><img src="<?php echo $row['photo_name'];?>" class="img-responsive" alt="Image not found" onerror="this.src='/media/default/default_building.png ';"> </div>
        <div class=" col-md-9"> 
          <div class="alert alert-info" role="alert">
            <strong>Podstawowe informacje:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa:</strong></td>
                <td><?php echo $row['theater_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Adres:</strong></td>
                <td><?php echo "ul. $row[street] $row[number], $row[postal_code] $row[city]"; ?></td>
              </tr>                 
            </tbody>
          </table>
        </div>
      </div>
      <br/>
      <div class="row">
        <div class="col-md-12"> 
          <table class="table table-user-information">
            <div class="alert alert-info" role="alert">
              <strong>Spekakle odgrywane w tym teatrze:</strong>
            </div>
            <tbody>
                <?php
                  //select with spectacles
                  $query = mysqli_query($link, 
                  "select distinct spectacle_name, description, id_spectacle from Shows NATURAL JOIN Hall NATURAL JOIN Spectacle where id_theater='$id';");
                  while ($row = mysqli_fetch_assoc($query)){
                    echo "<tr><td><a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong>$row[spectacle_name]</strong></a>:</td>";
                    echo "<td>$row[description]</td></tr>";
                  }
                ?> 
              </tbody>
            </table>
          </div>
      </div>
  </div>
</div>

<?php
  require('../shared/footer.php');
?>