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

  $query = "select id_spectacle, spectacle_name, duration, date_of_premiere, id_genre, description, photo_name from Spectacle NATURAL JOIN Genre WHERE id_spectacle='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database show fetch fail.";
    exit();
  }
  $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
  $row['duration'] = htmlspecialchars($row['duration']);
  $row['description'] = htmlspecialchars($row['description']);
  $row['photo_name'] = htmlspecialchars($row['photo_name']);
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/spectacle/"><button class='btn btn-info'>Powrót do listy spektaklu</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateSpectacle();">
      <div class="form-group">
          <label for="spectacle_name">Nazwa spektaklu:</label>
          <input type="text" class="form-control" id="spectacle_name" value="<?php echo $row['spectacle_name']; ?>" required>
      </div>
      <div class="form-group">
          <label for="duration">Czas trwania:</label>
          <input type="text" class="form-control" id="duration" value="<?php echo $row['duration']; ?>" required>
      </div>
      <div class="form-group">
          <label for="date_of_premiere">Data występu:</label>
          <input type="datetime-local" class="form-control" id="date_of_premiere" value="<?php echo date('Y-m-d\TH:i', strtotime($row['date_of_premiere'])); ?>" required>
      </div>
    <div class="form-group">
        <label for="id_genre">Gatunek:</label>
        <?php
          $query = mysqli_query($link, "select id_genre, genre from Genre;");
          echo "<select class=\"form-control\" id=\"id_genre\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $row['genre'] = htmlspecialchars($row['genre']);
                if($rowSelect['id_genre'] == $row['id_genre']) {
                  echo "<option value=\"$rowSelect[id_genre]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_genre]\">";
                }
                echo "$rowSelect[genre]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
          <label for="description">Opis:</label>
          <input type="text" class="form-control" id="description" value="<?php echo $row['description']; ?>" required>
      </div>
      <div class="form-group">
        <label for="photo_name">Wybierz zdjęcie osoby:</label>
        <?php
          $dir = '../../media/';
          $files = array_diff(scandir($dir), array('..', '.'));
          echo "<select class=\"form-control\" id=\"photo_name\" >";
          foreach ($files as $value){
            if($value == $row['photo_name']) {
              echo "<option value=\"{$value}\" selected>{$value}</option>>";
            }
            else {
              echo "<option value=\"{$value}\">{$value}</option>>";
            }
          }
          echo "</select>";
        ?>
        <input type="button" onclick="location.href='../images/'" value="Dodaj zdjęcie"> 
      </div>

      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryUpdateSpectacle() {
      $.post( "helpers/spectacle_helper.php", 
      {id_genre: $("#id_genre option:selected").val(), spectacle_name: $("#spectacle_name").val(),
      date_of_premiere: $("#date_of_premiere").val(), description: $("#description").val(), photo_name: $("#photo_name").val(),
      duration: $("#duration").val(), id_spectacle: <?php echo $row['id_spectacle']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji występu.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano występ.').attr('class', 'alert alert-success');
        }
      });
  }

</script>