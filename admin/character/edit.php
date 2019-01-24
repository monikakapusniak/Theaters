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

  $query = "select id_character, character_name, id_spectacle, spectacle_name from Characters NATURAL JOIN Spectacle WHERE id_character='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database address fetch fail.";
    exit();
  }
  $row['character_name'] = htmlspecialchars($row['character_name']);
  $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/character/"><button class='btn btn-info'>Powrót do listy postaci</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateCharacter();">
      <div class="form-group">
          <label for="street">Nazwa postaci:</label>
          <input type="text" class="form-control" id="character_name" value="<?php echo $row['character_name']; ?>" required>
      </div>
      <div class="form-group">
        <label for="id_city">Spektakl:</label>
        <?php
          $query = mysqli_query($link, "select id_spectacle, spectacle_name from Spectacle;");
          echo "<select class=\"form-control\" id=\"id_spectacle\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['spectacle_name'] = htmlspecialchars($rowSelect['spectacle_name']);
                if($rowSelect['id_spectacle'] == $row['id_spectacle']) {
                  echo "<option value=\"$rowSelect[id_spectacle]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_spectacle]\">";
                }
                echo "$rowSelect[spectacle_name]</option>";
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
  function tryUpdateCharacter() {
      $.post( "helpers/character_helper.php", 
      {id_spectacle: $('#id_spectacle').val(), character_name: $('#character_name').val(), id_character: <?php echo $row['id_character']; ?>}, function() {
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