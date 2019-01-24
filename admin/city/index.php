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
      
      $result = mysqli_query($link, "delete from City where id_city = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć miasta, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateCity();">Dodaj miasto</button>
    
    <div id="createCity" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania miasta.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateUser();">
          <div class="form-group">
              <label for="city">Nazwa miasta:</label>
              <input type="text" class="form-control" id="city" required>
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
    <table id="cities" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Miasto</th>
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
    $.post( "helpers/city_helper.php", {city: $('#city').val(),}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania miasta.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#cities').DataTable().ajax.reload();
        $('#createCity').find('input:text').val('');
        toggleCreateCity();
        $('#createInfo').html('Pomyślnie dodano miasto.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateCity() {
    var createCityDiv = document.getElementById("createCity");
    if (createCityDiv.style.display === "none") {
      createCityDiv.style.display = "block";
    } else {
      createCityDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#cities').DataTable({
      ajax: 'helpers/fetch_cities.php',
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

