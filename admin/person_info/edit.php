<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); 
  require('../../shared/header.php');
  require('../../shared/navbar.php');

  if($user_type != "Admin") {
      header("location: /shared/error.php");
  }

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../../database_connection.php';

  $query = "select description, id_person, date_of_info, id_person_info from Person_info where id_person_info='$id';";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database city fetch fail.";
    exit();
  }
  $row['description'] = htmlspecialchars($row['description']);  
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/person_info/"><button class='btn btn-info'>Powrót do listy informacji o osobach</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdatePersonInfo();">
      <div class="form-group">
        <label for="id_person">Osoba:</label>
        <?php
          $query = mysqli_query($link, "select id_person, firstname, lastname from Person;");
          echo "<select class=\"form-control\" id=\"id_person\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                if($rowSelect['id_person'] == $row['id_person']) {
                  echo "<option value=\"$rowSelect[id_person]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_person]\">";
                }
                echo "$rowSelect[firstname] $rowSelect[lastname]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
          <label for="description">Opis:</label>
          <input type="text" class="form-control" id="description" value="<?php echo $row['description']; ?>" required>
      </div>

      <div class="form-group">
          <label for="date_of_info">Data informacji:</label>
          <input type="datetime-local" class="form-control" id="date_of_info" value="<?php echo date('Y-m-d\TH:i', strtotime($row['date_of_info'])); ?>" required>
      </div>
      
      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryUpdatePersonInfo() {
      $.post( "helpers/personinfo_helper.php", 
      {id_person: $('#id_person').val(), description: $('#description').val(), date_of_info: $('#date_of_info').val(),
      id_person_info: <?php echo $row['id_person_info']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji miasta.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano miasto.').attr('class', 'alert alert-success');
        }
      });
  }
</script>