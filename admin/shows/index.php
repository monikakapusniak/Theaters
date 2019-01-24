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
      
      $result = mysqli_query($link, "delete from Shows where id_show = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć informacji o występie, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateShow();">Dodaj występ</button>
    
    <div id="createShow" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania występu.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateUser();">
        <div class="form-group">
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

          <div class="form-group">
              <label for="show_date">Data występu:</label>
              <input type="datetime-local" class="form-control" id="show_date" required>
          </div>

          <div class="form-group">
            <label for="id_hall">Sala:</label>
            
            <?php
              //select with halls
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_hall, hall_name from Hall;");
              echo "<select class=\"form-control\" id=\"id_hall\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_hall}\">{$hall_name}</option>";
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
      <strong>Lista występów.</strong>
    </div>
    <table id="shows" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Spektakl</th>
            <th>Data występu</th>
            <th>Sala</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateUser() {
    $.post( "helpers/show_helper.php", {id_spectacle: $("#id_spectacle option:selected").val(), show_date: $("#show_date").val(), id_hall: $("#id_hall option:selected").val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania występu.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#shows').DataTable().ajax.reload();
        $('#createShow').find('input:text').val('');
        toggleCreateShow();
        $('#createInfo').html('Pomyślnie dodano występ.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateShow() {
    var createShowDiv = document.getElementById("createShow");
    if (createShowDiv.style.display === "none") {
      createShowDiv.style.display = "block";
    } else {
      createShowDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#shows').DataTable({
      ajax: 'helpers/fetch_shows.php',
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

