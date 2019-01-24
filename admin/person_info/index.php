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
      
      $result = mysqli_query($link, "delete from Person_info where id_person_info = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć informacji o osobie, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreatePersonInfo();">Dodaj informację o osobie:</button>
    
    <div id="createPersonInfo" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania informacji o osobie.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreatePersonInfo();">
          <div class="form-group">
              <label for="description">Opis:</label>
              <input type="text" class="form-control" id="description" required>
          </div>
          <div class="form-group">
            <label for="id_person">Osoba:</label>
            <?php
              //select with halls
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_person, firstname, lastname from Person;");
              echo "<select class=\"form-control\" id=\"id_person\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_person}\">{$firstname} {$lastname}</option>";
                }
              echo "</select>";
            ?>
          </div>

          <div class="form-group">
              <label for="date_of_info">Data premiery:</label>
              <input type="datetime-local" class="form-control" id="date_of_info" required>
          </div>

          <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista miast.</strong>
    </div>
    <table id="personinfos" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Osoba</th>
            <th>Informacja</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreatePersonInfo() {
    $.post( "helpers/personinfo_helper.php", {id_person: $('#id_person').val(), description: $('#description').val(), date_of_info: $('#date_of_info').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania informacji o osobie.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#personinfos').DataTable().ajax.reload();
        $('#createCity').find('input:text').val('');
        toggleCreatePersonInfo();
        $('#createInfo').html('Pomyślnie dodano informacje o osobie.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreatePersonInfo() {
    var createPersonInfoDiv = document.getElementById("createPersonInfo");
    if (createPersonInfoDiv.style.display === "none") {
      createPersonInfoDiv.style.display = "block";
    } else {
      createPersonInfoDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#personinfos').DataTable({
      ajax: 'helpers/fetch_personinfo.php',
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

