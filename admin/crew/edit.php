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

  $query = "select id_person, id_crew_type, id_crew, sh.id_show from Crew c NATURAL JOIN Crew_type natural join Person Left join Shows sh on sh.id_show = c.id_show left join Spectacle sp on sp.id_spectacle = sh.id_spectacle where id_crew='$id';";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database crew fetch fail.";
    exit();
  }
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/crew/"><button class='btn btn-info'>Powrót do listy osób związanych z teatrem</button></a><br/><br/>
    <div id="updateInfo"></div>
    <form action="javascript:void(0)" onSubmit="tryUpdateCrew();">
      <div class="form-group">
        <label for="id_person">Imię i nazwisko:</label>
        <?php
          $query = mysqli_query($link, "select id_person, firstname, lastname from Person;");
          echo "<select class=\"form-control\" id=\"id_person\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                if($rowSelect['id_person'] == $row['id_person']) {
                  $rowSelect['firstname'] = htmlspecialchars($rowSelect['firstname']);
                  $rowSelect['lastname'] = htmlspecialchars($rowSelect['lastname']);
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
        <label for="id_crew_type">Typ osoby:</label>
        <?php
          $query = mysqli_query($link, "select id_crew_type, kind from Crew_type;");
          echo "<select class=\"form-control\" id=\"id_crew_type\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['kind'] = htmlspecialchars($rowSelect['kind']);
                if($rowSelect['id_crew_type'] == $row['id_crew_type']) {
                  echo "<option value=\"$rowSelect[id_crew_type]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_crew_type]\">";
                }
                echo "$rowSelect[kind]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
        <label for="id_show">Spektakl:</label>
        <?php
          $query = mysqli_query($link, "select id_show, spectacle_name, convertDatetimeToDateString(show_date) as show_date from Shows Natural join Spectacle;");
          echo "<select class=\"form-control\" id=\"id_show\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['spectacle_name'] = htmlspecialchars($rowSelect['spectacle_name']);
                $rowSelect['show_date'] = htmlspecialchars($rowSelect['show_date']);
                if($rowSelect['id_show'] == $row['id_show']) {
                  echo "<option value=\"$rowSelect[id_show]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_show]\">";
                }
                echo "$rowSelect[spectacle_name] $rowSelect[show_date]</option>";
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
  function tryUpdateCrew() {
      $.post( "helpers/crew_helper.php", 
      {id_crew_type: $('#id_crew_type').val(), id_person: $('#id_person').val(), id_show: $('#id_show').val(), id_crew: <?php echo $row['id_crew']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji osoby związanej z teatrem.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano dodano osobe.').attr('class', 'alert alert-success');
        }
      });
  }
</script>