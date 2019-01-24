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
      
      $result = mysqli_query($link, "delete from Crew where id_crew = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć obsługi, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateCrew();">Dodaj osobę związaną ze spektaklem</button>
    
    <div id="createCrew" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania osobę związanej ze spektaklem.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateCrew();">
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
            <label for="id_crew_type">Typ osoby:</label>
            <?php
              //select with people
              $query = mysqli_query($link, "select id_crew_type, kind from Crew_type;");
              echo "<select class=\"form-control\" id=\"id_crew_type\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_crew_type}\">{$kind}</option>";
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
      <strong>Lista osób związanych ze spektaklem.</strong>
    </div>
    <table id="crews" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Typ osoby</th>
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
  function tryCreateCrew() {
    $.post( "helpers/crew_helper.php", {id_crew_type: $('#id_crew_type').val(), id_person: $('#id_person').val(), id_show: $('#id_show').val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania obsady.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#crews').DataTable().ajax.reload();
        $('#createCrew').find('input:text').val('');
        toggleCreateCrew();
        $('#createInfo').html('Pomyślnie dodano osobę związaną ze spektaklem.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateCrew() {
    var createCrewDiv = document.getElementById("createCrew");
    if (createCrewDiv.style.display === "none") {
      createCrewDiv.style.display = "block";
    } else {
      createCrewDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#crews').DataTable({
      ajax: 'helpers/fetch_crews.php',
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
