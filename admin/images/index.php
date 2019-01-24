<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
  require('../../shared/header.php');
  require('../../shared/navbar.php');
  include '../../database_connection.php';

  if($user_type != "Admin") {
    header("location: /shared/error.php");
}
?>

<div class="container">  
  <div class="panel panel-info">
    <div class="panel-body">

      <form action="upload_helper.php" method="post" enctype="multipart/form-data">
        Wybierz zdjęcie do dodania:
        <input class="form-control-file" type="file" name="fileToUpload" id="fileToUpload">
        <input class="btn btn-primary" type="submit" value="Dodaj zdjęcie" name="submit">
      </form>

    
    </div>
  </div>
</div>


<?php 
  require('../../shared/footer.php');
?>
