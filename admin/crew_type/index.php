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
      
      $result = mysqli_query($link, "delete from Crew_type where id_crew_type = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć posady, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateCrewType();">Dodaj posadę</button>
    
    <div id="createCrewType" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania posady.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateCrewType();">
          <div class="form-group">
              <label for="kind">Nazwa posady:</label>
              <input type="text" class="form-control" id="kind" required>
          </div>
            <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista gatunków.</strong>
    </div>
    <table id="crewtypes" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Posada</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateCrewType() {
    $.post( "helpers/crewtype_helper.php", {kind: $('#kind').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania posady.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#crewtypes').DataTable().ajax.reload();
        $('#createCrewType').find('input:text').val('');
        toggleCreateCrewType();
        $('#createInfo').html('Pomyślnie dodano posade.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateCrewType() {
    var createCrewTypeDiv = document.getElementById("createCrewType");
    if (createCrewTypeDiv.style.display === "none") {
      createCrewTypeDiv.style.display = "block";
    } else {
      createCrewTypeDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#crewtypes').DataTable({
      ajax: 'helpers/fetch_crewtypes.php',
      columnDefs: [
      {
        targets: 1,
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

