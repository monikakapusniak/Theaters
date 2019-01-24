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
      
      $result = mysqli_query($link, "delete from Genre where id_genre = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć gatunku, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateGenre();">Dodaj gatunek:</button>
    
    <div id="createGenre" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania gatunku.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateGenre();">
          <div class="form-group">
              <label for="genre">Nazwa gatunku:</label>
              <input type="text" class="form-control" id="genre" required>
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
    <table id="genres" class="display" style="width:100%">
      <thead>
          <tr>
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
  function tryCreateGenre() {
    $.post( "helpers/genre_helper.php", {genre: $('#genre').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania gatunku.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#genres').DataTable().ajax.reload();
        $('#createCity').find('input:text').val('');
        toggleCreateGenre();
        $('#createInfo').html('Pomyślnie dodano gatunek.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateGenre() {
    var createGenreDiv = document.getElementById("createGenre");
    if (createGenreDiv.style.display === "none") {
      createGenreDiv.style.display = "block";
    } else {
      createGenreDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#genres').DataTable({
      ajax: 'helpers/fetch_genres.php',
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
