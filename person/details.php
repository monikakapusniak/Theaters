<?php 
  require('../shared/header.php');
  require('../shared/navbar.php');

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../database_connection.php';

  $query = 
  "select gender, city, createUrlPathToImg(photo_name) as photo_name, firstname, lastname, convertDatetimeToDateString(date_of_birth) as date_of_birth, convertDatetimeToDateString(date_of_death) as date_of_death from Person NATURAL JOIN City NATURAL JOIN Gender where id_person='$id';";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database person fetch fail.";
    exit();
  }

  $row['gender'] = htmlspecialchars($row['gender']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['photo_name'] = htmlspecialchars($row['photo_name']);
  $row['firstname'] = htmlspecialchars($row['firstname']);
  $row['lastname'] = htmlspecialchars($row['lastname']);
  $row['date_of_birth'] = htmlspecialchars($row['date_of_birth']);
  $row['date_of_death'] = htmlspecialchars($row['date_of_death']);
?>
<div class="container">  
  <a href="/person"><button class='btn btn-info'>Powrót do listy osób związnych z teatrem</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-3" align="center"><img src='<?php echo $row['photo_name'];?>'' class="img-responsive" alt="Image not found" onerror="this.src='/media/default/default_person.png ';"> </div>
        <div class=" col-md-9"> 
          <div class="alert alert-info" role="alert">
            <strong>Podstawowe informacje:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Imię:</strong></td>
                <td><?php echo $row['firstname']; ?></td>
              </tr>
              <tr>
                <td><strong>Nazwisko:</strong></td>
                <td><?php echo $row['lastname']; ?></td>
              </tr>
              <tr>
                <td><strong>Płeć:</strong></td>
                <td><?php echo $row['gender']; ?></td>
              </tr>
              <tr>
                <td><strong>Miejsce zamieszkania</strong></td>
                <td><?php echo $row['city']?></td>
              </tr>
              <tr>
                <td><strong>Data urodzenia:</strong></td>
                <td><?php echo $row['date_of_birth']?></td>
              </tr>
              <tr>
                <td><strong>Data śmierci:</strong></td>
                <td><?php echo $row['date_of_death']?></td>
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
              <strong>Informacje/ciekawostki:</strong>
            </div>
            <tbody>
                <?php
                  //select with person_info
                  $query = mysqli_query($link, 
                  "select description, DATE_FORMAT(date_of_info,'%d/%m/%Y') as date_of_info from Person_info where id_person='$id';");
                  while ($row = mysqli_fetch_assoc($query)){
                      echo "<tr><td><strong>$row[date_of_info]</strong></td>";
                      echo "<td>$row[description]</td></tr>";
                  }
                ?> 
              </tbody>
            </table>
          </div>
      </div>

      <br/>
      <div class="row">
        <div class="col-md-6"> 
          <table class="table table-user-information">
            <div class="alert alert-info" role="alert">
              <strong>Aktor w:</strong>
            </div>
            <tbody>
                <?php
                  //select with casts
                  $query = mysqli_query($link, 
                  "select distinct role_type, character_name, spectacle_name, id_spectacle from Casts NATURAL JOIN Shows NATURAL JOIN Spectacle NATURAL JOIN Role_type NATURAL JOIN Characters where id_person='$id';");
                  while ($row = mysqli_fetch_assoc($query)){
                    echo "<tr><td><a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong>$row[spectacle_name]</strong></a>:</td>";
                    echo "<td>$row[character_name] ($row[role_type])</td></tr>";
                  }
                ?> 
              </tbody>
            </table>
          </div>
          <div class="col-md-6"> 
          <table class="table table-user-information">
            <div class="alert alert-info" role="alert">
              <strong>Obsługa spektaklu w:</strong>
            </div>
            <tbody>
                <?php
                  //select with crew
                  $query = mysqli_query($link, 
                  "select distinct kind, spectacle_name, id_spectacle from Crew NATURAL JOIN Shows NATURAL JOIN Spectacle NATURAL JOIN Crew_type where id_person='$id';");
                  while ($row = mysqli_fetch_assoc($query)){
                    echo "<tr><td><a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong>$row[spectacle_name]</strong></a>:</td>";
                    echo "<td>$row[kind]</td></tr>";
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