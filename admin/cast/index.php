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

      $result = mysqli_query($link, "delete from Casts where id_cast = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć obsady, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateCast();">Dodaj obsadę</button>
    
    <div id="createCast" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania obsady.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateCast();">
        <div class="form-group">
            <label for="id_character">Postać:</label>
            <?php
              //select with people
              $query = mysqli_query($link, "select id_character, character_name from Characters;");
              echo "<select class=\"form-control\" id=\"id_character\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_character}\">{$character_name}</option>";
                }
              echo "</select>";
            ?>
          </div>
          <div class="form-group">
            <label for="id_role_type">Typ roli:</label>
            <?php
              //select with role_types
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_role_type, role_type from Role_type;");
              echo "<select class=\"form-control\" id=\"id_role_type\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_role_type}\">{$role_type}</option>";
                }
              echo "</select>";
            ?>
          </div>
          <div class="form-group">
            <label for="id_person">Osoba:</label>
            <?php
              //select with people
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
            <label for="id_show">Spektakl:</label>
            <?php
              //select with people
              $query = mysqli_query($link, "select id_show, spectacle_name, convertDatetimeToDateString(show_date) as show_date from Shows Natural join Spectacle;");
              echo "<select class=\"form-control\" id=\"id_show\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_show}\">{$spectacle_name} {$show_date}</option>";
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
      <strong>Lista obsady.</strong>
    </div>
    <table id="casts" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Postać</th>
            <th>Osoba</th>
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
  function tryCreateCast() {
    $.post( "helpers/cast_helper.php", {id_character: $('#id_character').val(), id_person: $('#id_person').val(), id_role_type: $('#id_role_type').val(), id_show: $('#id_show').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania obsady.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#casts').DataTable().ajax.reload();
        $('#createCast').find('input:text').val('');
        toggleCreateCast();
        $('#createInfo').html('Pomyślnie dodano obsadę.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateCast() {
    var createCastDiv = document.getElementById("createCast");
    if (createCastDiv.style.display === "none") {
      createCastDiv.style.display = "block";
    } else {
      createCastDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#casts').DataTable({
      ajax: 'helpers/fetch_casts.php',
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

