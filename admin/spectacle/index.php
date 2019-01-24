<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  require('../../shared/header.php');
  require('../../shared/navbar.php');

  if($user_type != "Admin") {
      header("location: /shared/error.php");
  }
  
?>

<div class="container">  
  <div class="col-md-12">
  
<?php
  if(isset($_GET['id']))
  {
      $id = mysqli_real_escape_string($link, $_GET['id']);
      
      $result = mysqli_query($link, "delete from Spectacle where id_spectacle = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć informacji o spektaklu, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateSpectacle();">Dodaj spektakl</button>
    
    <div id="createSpectacle" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania spektaklu.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateSpectacle();">
          <div class="form-group">
              <label for="spectacle_name">Nazwa spektaklu:</label>
              <input type="text" class="form-control" id="spectacle_name" required>
          </div>
          <div class="form-group">
              <label for="duration">Czas trwania (np. 200 min.):</label>
              <input type="text" class="form-control" id="duration" required>
          </div>
          <div class="form-group">
              <label for="date_of_premiere">Data premiery:</label>
              <input type="datetime-local" class="form-control" id="date_of_premiere" required>
          </div>
          <div class="form-group">
            <label for="id_genre">Gatunek:</label>
            <?php
              //select with halls
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_genre, genre from Genre;");
              echo "<select class=\"form-control\" id=\"id_genre\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_genre}\">{$genre}</option>";
                }
              echo "</select>";
            ?>
          </div>
          <div class="form-group">
              <label for="description">Opis:</label>
              <input type="textarea" rows="6" class="form-control" id="description" required>
          </div>
          <div class="form-group">
            <label for="photo_name">Wybierz zdjęcie spektaklu:</label>
            <?php
              $dir = '../../media/';
              $files = array_diff(scandir($dir), array('..', '.'));
              echo "<select class=\"form-control\" id=\"photo_name\" >";
              foreach ($files as $value){
                echo "<option value=\"{$value}\">{$value}</option>";
              }
              echo "</select>";
            ?>

            <input type="button" onclick="location.href='../images/'" value="Dodaj zdjęcie"> 
          </div>

          <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista spektakli.</strong>
    </div>
    <table id="spectacles" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Spektakl</th>
            <th>Data premiery</th>
            <th>Gatunek</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateSpectacle() {
    $.post( "helpers/spectacle_helper.php", {id_genre: $("#id_genre option:selected").val(), spectacle_name: $("#spectacle_name").val(),
      date_of_premiere: $("#date_of_premiere").val(), description: $("#description").val(), photo_name: $("#photo_name").val(),
      duration: $("#duration").val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania spektaklu.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#spectacles').DataTable().ajax.reload();
        $('#createShow').find('input:text').val('');
        toggleCreateSpectacle();
        $('#createInfo').html('Pomyślnie dodano spektakl.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateSpectacle() {
    var createSpectacleDiv = document.getElementById("createSpectacle");
    if (createSpectacleDiv.style.display === "none") {
      createSpectacleDiv.style.display = "block";
    } else {
      createSpectacleDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#spectacles').DataTable({
      ajax: 'helpers/fetch_spectacles.php',
      columnDefs: [
      {
        targets: 3,
        render: function (data)
        {
          var detailsButton = "<a href=\"details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";
          var editButton = "<a href=\"edit.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-warning'>Edycja</button></a>";
          var deleteButton = "<a href=\"?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-danger'>Usuń</button></a>";
          return detailsButton + " " + editButton + " " + deleteButton;
        }
      }]
    });
  });
</script>
