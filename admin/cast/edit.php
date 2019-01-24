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

  $query = "select id_cast, id_character, id_person, id_role_type, id_show from Casts where id_cast='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database address fetch fail.";
    exit();
  }
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/cast/"><button class='btn btn-info'>Powrót do listy postaci</button></a><br/><br/>
    <div id="updateInfo"></div>
    <form action="javascript:void(0)" onSubmit="tryUpdateCast();">
      <div class="form-group">
        <label for="id_character">Postać:</label>
        <?php
          $query = mysqli_query($link, "select id_character, character_name from Characters;");
          echo "<select class=\"form-control\" id=\"id_character\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['character_name'] = htmlspecialchars($rowSelect['character_name']);
                if($rowSelect['id_character'] == $row['id_character']) {
                  echo "<option value=\"$rowSelect[id_character]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_character]\">";
                }
                echo "$rowSelect[character_name]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
        <label for="id_role_type">Typ roli:</label>
        <?php
          $query = mysqli_query($link, "select id_role_type, role_type from Role_type;");
          echo "<select class=\"form-control\" id=\"id_role_type\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['role_type'] = htmlspecialchars($rowSelect['role_type']);
                if($rowSelect['id_role_type'] == $row['id_role_type']) {
                  echo "<option value=\"$rowSelect[id_role_type]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_role_type]\">";
                }
                echo "$rowSelect[role_type]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
        <label for="id_person">Osoba:</label>
        <?php
          $query = mysqli_query($link, "select id_person, firstname, lastname from Person;");
          echo "<select class=\"form-control\" id=\"id_person\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['firstname'] = htmlspecialchars($rowSelect['firstname']);
                $rowSelect['lastname'] = htmlspecialchars($rowSelect['lastname']);
                if($rowSelect['id_person'] == $row['id_person']) {
                  echo "<option value=\"$rowSelect[id_person]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_person]\">";
                }
                echo "$rowSelect[firstname] $rowSelect[lastname]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
        <label for="id_show">Spektakl:</label>
        <?php
          $query = mysqli_query($link, "select id_show, concat(spectacle_name, ' ', convertDatetimeToDateString(show_date)) as shows from Shows Natural join Spectacle;");
          echo "<select class=\"form-control\" id=\"id_show\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['shows'] = htmlspecialchars($rowSelect['shows']);
                if($rowSelect['id_show'] == $row['id_show']) {
                  echo "<option value=\"$rowSelect[id_show]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_show]\">";
                }
                echo "$rowSelect[shows]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryUpdateCast() {
      $.post( "helpers/cast_helper.php", 
      {id_character: $('#id_character').val(), id_person: $('#id_person').val(), id_role_type: $('#id_role_type').val(), id_show: $('#id_show').val(), id_cast: <?php echo $row['id_cast']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji postaci.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano postać.').attr('class', 'alert alert-success');
        }
      });
  }
</script>