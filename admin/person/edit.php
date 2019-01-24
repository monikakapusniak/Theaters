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

  $query = "select id_person, lastname, firstname, gender, id_gender, city, id_city, photo_name, date_of_birth, date_of_death from Person Natural JOIN (Gender, City) WHERE id_person='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database person fetch fail.";
    exit();

    $row['id_person'] = htmlspecialchars($row['id_person']);
    $row['lastname'] = htmlspecialchars($row['lastname']);
    $row['firstname'] = htmlspecialchars($row['firstname']);
    $row['gender'] = htmlspecialchars($row['gender']);
    $row['id_gender'] = htmlspecialchars($row['id_gender']);
    $row['city'] = htmlspecialchars($row['city']);
    $row['id_city'] = htmlspecialchars($row['id_city']);
    $row['photo_name'] = htmlspecialchars($row['photo_name']);
    $row['date_of_birth'] = htmlspecialchars($row['date_of_birth']);
    $row['date_of_death'] = htmlspecialchars($row['date_of_death']);
  }
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/person/"><button class='btn btn-info'>Powrót do listy osób</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryChangePerson();">
      <div class="form-group">
          <label for="lastname">Nazwisko:</label>
          <input type="text" class="form-control" id="lastname" value="<?php echo $row['lastname']; ?>" required>
      </div>

      <div class="form-group">
          <label for="firstname">Imię:</label>
          <input type="text" class="form-control" id="firstname" value="<?php echo $row['firstname']; ?>" required>
      </div>

      <div class="form-group">
        <label for="id_gender">Płeć:</label>
        <?php
          $query = mysqli_query($link, "select id_gender, gender from Gender;");
          echo "<select class=\"form-control\" id=\"id_gender\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
              $row['gender'] = htmlspecialchars($row['gender']);
              if($rowSelect['id_gender'] == $row['id_gender']) {
                echo "<option value=\"$rowSelect[id_gender]\" selected>";
              }
              else {
                echo "<option value=\"$rowSelect[id_gender]\">";
              }
              echo "$rowSelect[gender]</option>";
            }
          echo "</select>";
        ?>
      </div>

      <div class="form-group">
        <label for="id_city">Miasto:</label>
        <?php
          $query = mysqli_query($link, "select id_city, city from City;");
          echo "<select class=\"form-control\" id=\"id_city\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
              $row['city'] = htmlspecialchars($row['city']);
              if($rowSelect['id_city'] == $row['id_city']) {
                echo "<option value=\"$rowSelect[id_city]\" selected>";
              }
              else {
                echo "<option value=\"$rowSelect[id_city]\">";
              }
              echo "$rowSelect[city]</option>";
            }
          echo "</select>";
        ?>
      </div>

      <div class="form-group">
        <label for="photo_name">Wybierz zdjęcie osoby:</label>
        <?php
          $dir = '../../media/';
          $files = array_diff(scandir($dir), array('..', '.'));
          echo "<select class=\"form-control\" id=\"photo_name\" >";
          foreach ($files as $value){
            if($row['photo_name']==$value){
              echo "<option value=\"{$value}\" selected>{$value}</option>";
            }else{
              echo "<option value=\"{$value}\">{$value}</option>";
            }
          }
          echo "</select>";
        ?>

        <input type="button" onclick="location.href='../images/'" value="Dodaj zdjęcie"> 
      </div>

      <div class="form-group">
        <label for="date_of_birth">Data urodzenia:</label>
        <input type="date" class="form-control" id="date_of_birth" value="<?php echo date('Y-m-d', strtotime($row['date_of_birth'])); ?>">
      </div>
      
      <div class="form-group">
          <label for="date_of_death">Data śmierci:</label>
          <input type="date" class="form-control" id="date_of_death" value="<?php echo date('Y-m-d', strtotime($row['date_of_death'])); ?>">
      </div>

      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryChangePerson() {
      $.post( "helpers/person_helper.php", 
      {lastname: $('#lastname').val(), firstname: $('#firstname').val(), id_gender: $("#id_gender option:selected").val(), id_city: $("#id_city option:selected").val(), photo_name: $("#photo_name").val(), date_of_birth: $('#date_of_birth').val(), date_of_death: $('#date_of_death').val(), id_person: <?php echo $row['id_person']; ?>},
      function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji opisu osoby.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano opis osoby.').attr('class', 'alert alert-success');
        }
      });
  }
</script>