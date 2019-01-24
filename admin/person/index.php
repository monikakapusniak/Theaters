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
      $result = mysqli_query($link, "delete from Person where id_person = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć osoby, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
    }

    ?>
    <button type="button" class="btn btn-alert" onclick="toggleCreatePerson();">Dodaj osobę</button>
    
    <div id="createPerson" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania osoby.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreatePerson();">
          <div class="form-group">
              <label for="lastname">Nazwisko:</label>
              <input type="text" class="form-control" id="lastname" required>
          </div>
          
          <div class="form-group">
              <label for="firstname">Imię:</label>
              <input type="text" class="form-control" id="firstname" required>
          </div>

          <div class="form-group">
            <label for="id_gender">Płeć:</label>
            
            <?php
              //select with gender
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_gender, gender from Gender;");
              echo "<select class=\"form-control\" id=\"id_gender\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_gender}\">{$gender}</option>";
                }
              echo "</select>";
            ?>
          </div>

          <div class="form-group">
            <label for="id_city">Miasto:</label>
            
            <?php
              //select with cities
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_city, city from City;");
              echo "<select class=\"form-control\" id=\"id_city\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_city}\">{$city}</option>";
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
                echo "<option value=\"{$value}\">{$value}</option>";
              }
              echo "</select>";
            ?>

            <input type="button" onclick="location.href='../images/'" value="Dodaj zdjęcie"> 
          </div>

          <div class="form-group">
              <label for="date_of_birth">Data urodzenia:</label>
              <input type="date" class="form-control" id="date_of_birth">
          </div>
          
          <div class="form-group">
              <label for="date_of_death">Data śmierci:</label>
              <input type="date" class="form-control" id="date_of_death">
          </div>

          <button type="submit" class="btn btn-info" name="form_submit" >Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista osób związanych z teatrem.</strong>
    </div>

    <table id="people" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Nazwisko</th>
            <th>Imię</th>
            <th>Płeć</th>
            <th>Miasto rodzinne</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreatePerson() {
    $.post( "helpers/person_helper.php", {lastname: $('#lastname').val(), firstname: $("#firstname").val(), id_gender: $("#id_gender option:selected").val(), id_city: $("#id_city option:selected").val(), photo_name: $("#photo_name").val(), date_of_birth: $('#date_of_birth').val(), date_of_death: $('#date_of_death').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania osoby.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#people').DataTable().ajax.reload();
        $('#createPerson').find('input:text').val('');
        toggleCreatePerson();
        $('#createInfo').html('Pomyślnie dodano osobę.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreatePerson() {
    var createPersonDiv = document.getElementById("createPerson");
    if (createPersonDiv.style.display === "none") {
      createPersonDiv.style.display = "block";
    } else {
      createPersonDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#people').DataTable({
      ajax: 'helpers/fetch_people.php',
      columnDefs: [
      {
        targets: 4,
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