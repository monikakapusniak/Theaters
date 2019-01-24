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
      
      $result = mysqli_query($link, "delete from Characters where id_character = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć postaci, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
      
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateCharacter();">Dodaj postać</button>
    
    <div id="createCharacter" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania postaci.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateCharacter();">
          <div class="form-group">
            <div class="form-group">
              <label for="postal_code">Nazwa postaci:</label>
              <input type="text" class="form-control" id="character_name" required>
          </div>
            <label for="id_spectacle">Spektakl:</label>
            <?php
              //select with spectacles
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_spectacle, spectacle_name from Spectacle;");
              echo "<select class=\"form-control\" id=\"id_spectacle\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_spectacle}\">{$spectacle_name}</option>";
                }
              echo "</select>";
            ?>
          </div>
          <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista postaci.</strong>
    </div>
    <table id="characters" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Nazwa postaci</th>
            <th>Spektakl</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateCharacter() {
    $.post( "helpers/character_helper.php", {id_spectacle: $('#id_spectacle').val(), character_name: $('#character_name').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania postaci.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#characters').DataTable().ajax.reload();
        $('#createCharacter').find('input:text').val('');
        toggleCreateCharacter();
        $('#createInfo').html('Pomyślnie dodano postac.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateCharacter() {
    var createCharacterDiv = document.getElementById("createCharacter");
    if (createCharacterDiv.style.display === "none") {
      createCharacterDiv.style.display = "block";
    } else {
      createCharacterDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#characters').DataTable({
      ajax: 'helpers/fetch_characters.php',
      columnDefs: [
      {
        targets: 2,
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


