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
      
      $result = mysqli_query($link, "delete from Hall where id_hall = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć sali, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateHall();">Dodaj salę</button>
    
    <div id="createHall" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania sali.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateUser();">
          <div class="form-group">
              <label for="hall_name">Nazwa sali:</label>
              <input type="text" class="form-control" id="hall_name" required>
          </div>

          <div class="form-group">
            <label for="id_theater">Teatr:</label>
            
            <?php
              //select with theaters
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_theater, theater_name from Theater;");
              echo "<select class=\"form-control\" id=\"id_theater\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_theater}\">{$theater_name}</option>";
                }
              echo "</select>";
            ?>
          </div>

          <div class="form-group">
              <label for="capacity">Pojemność:</label>
              <input type="text" class="form-control" id="capacity" required>
          </div>

          <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista sal.</strong>
    </div>
    <table id="halls" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Nazwa sali</th>
            <th>Teatr</th>
            <th>Pojemność</th>
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
    $.post( "helpers/hall_helper.php", {hall_name: $('#hall_name').val(), id_theater: $("#id_theater option:selected").val(), capacity: $("#capacity").val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania sali.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#halls').DataTable().ajax.reload();
        $('#createHall').find('input:text').val('');
        toggleCreateHall();
        $('#createInfo').html('Pomyślnie dodano salę.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateHall() {
    var createHallDiv = document.getElementById("createHall");
    if (createHallDiv.style.display === "none") {
      createHallDiv.style.display = "block";
    } else {
      createHallDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#halls').DataTable({
      ajax: 'helpers/fetch_halls.php',
      columnDefs: [
      {
        targets: 3,
        render: function (data)
        {
          console.log(encodeURIComponent(data));

          var detailsButton = "<a href=\"details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";
          var editButton = "<a href=\"edit.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-warning'>Edycja</button></a>";
          var deleteButton = "<a href=\"?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-danger'>Usuń</button></a>";
          return detailsButton + " " + editButton + " " + deleteButton;
        }
      }]
    });
  });
</script>
