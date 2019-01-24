<?php 
  require('../shared/header.php');
  require('../shared/navbar.php');

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../database_connection.php';

  $query = "select theater_name, hall_name, capacity, street, number, postal_code, city, id_spectacle, convertDatetimeToDateString(show_date) as show_date, id_theater from Shows NATURAL JOIN Hall NATURAL JOIN Theater NATURAL JOIN Address NATURAL JOIN City where id_show='$id';";
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

  $row['theater_name'] = htmlspecialchars($row['theater_name']);
  $row['hall_name'] = htmlspecialchars($row['hall_name']);
  $row['capacity'] = htmlspecialchars($row['capacity']);
  $row['street'] = htmlspecialchars($row['street']);
  $row['postal_code'] = htmlspecialchars($row['postal_code']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['show_date'] = htmlspecialchars($row['show_date']);
?>
<div class="container">
  <a href="/spectacle/details.php?id=<?php echo $row['id_spectacle'];?>"><button class='btn btn-info'>Powrót do spekaklu</button></a><br/><br/>  
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Miejsce i data wystawienia spektaklu:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa teatru:</strong></td>
                <td><a href="/theater/details.php?id=<?php echo $row['id_theater'];?>"> <?php echo $row['theater_name']; ?></a></td>
              </tr>
              <tr>
                <td><strong>Adres:</strong></td>
                <td><?php echo "ul. $row[street] $row[number], $row[postal_code] $row[city]"; ?></td>
              </tr>
              <tr>
                <td><strong>Nazwa sali:</strong></td>
                <td><?php echo $row['hall_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Pojemność sali:</strong></td>
                <td><?php echo $row['capacity']; ?></td>
              </tr>
              <tr>
                <td><strong>Data oraz godzina:</strong></td>
                <td><?php echo $row['show_date']; ?></td>
              </tr>             
            </tbody>
          </table>
        </div>
      </div>

      <br/>
      <div class="row">
        <div class=" col-md-6"> 
          <table class="table table-user-information">
          <div class="alert alert-info" role="alert">
            <strong>Aktorzy:</strong>
          </div>
            <tbody>
              <?php
                //select with cast
                $query = mysqli_query($link, 
                "select role_type, character_name, firstname, lastname, id_person from Casts NATURAL JOIN Characters NATURAL JOIN Person NATURAL JOIN Role_type where id_show='$id';");
                while ($row = mysqli_fetch_assoc($query)){
                    echo "<tr><td><a href=\"/person/details.php?id=$row[id_person]\"><strong>$row[firstname] $row[lastname]</strong></a>:</td>";
                    echo "<td>$row[character_name] ($row[role_type])</td></tr>";
                  }
              ?> 
            </tbody>
          </table>
        </div>
        <div class=" col-md-6"> 
          <table class="table table-user-information">
          <div class="alert alert-info" role="alert">
            <strong>Osoby związane ze spektaklem:</strong>
          </div>
            <tbody>
            <?php
                //select with crew
                $query = mysqli_query($link, 
                "select kind, firstname, lastname, id_person from Crew NATURAL JOIN Person NATURAL JOIN Crew_type where id_show='$id';");
                while ($row = mysqli_fetch_assoc($query)){
                      echo "<tr><td><a href=\"/person/details.php?id=$row[id_person]\"><strong>$row[firstname] $row[lastname]</strong></a>:</td>";
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