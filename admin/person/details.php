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

  $query = "select Person.id_person, Person.lastname, Person.firstname, Gender.gender, City.city, Person.photo_name, Person.date_of_birth, Person.date_of_death from Person JOIN (Gender, City) on (Person.id_gender=Gender.id_gender and Person.id_city=City.id_city) WHERE id_person='$id'";
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
?>

<div class="container">
  <a href="/admin/person/"><button class='btn btn-info'>Powrót do listy osób</button></a><br/><br/>
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
                <td><strong>Nazwisko:</strong></td>
                <td><?php echo $row['lastname']; ?></td>
              </tr>
              <tr>
                <td><strong>Imię:</strong></td>
                <td><?php echo $row['firstname']; ?></td>
              </tr>
              <tr>
                <td><strong>Płeć:</strong></td>
                <td><?php echo $row['gender']; ?></td>
              </tr>
              <tr>
                <td><strong>Miasto:</strong></td>
                <td><?php echo $row['city']; ?></td>
              </tr>
              <tr>
                <td><strong>Nazwa zdjęcia:</strong></td>
                <td><?php echo $row['photo_name']; ?></td>
              </tr>       
              <tr>
                <td><strong>Data urodzenia:</strong></td>
                <td><?php echo $row['date_of_birth']; ?></td>
              </tr>   
              <tr>
                <td><strong>Data śmierci:</strong></td>
                <td><?php echo $row['date_of_death']; ?></td>
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