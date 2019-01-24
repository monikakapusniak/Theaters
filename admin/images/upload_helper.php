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
  <div class="panel panel-info">
    <div class="panel-body">
    <?php
$target_dir = "../../media/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "Plik jest zdjęciem - " . $check["mime"] . ".<br>";
        $uploadOk = 1;
    } else {
        echo "Plik nie jest zdjęciem.<br>";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Taki plik już istnieje w tej lokalizacji.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Twój plik jest za duży (>5mb).<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Tylko formaty JPG, JPEG, PNG, GIF są dozwolone.<br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Twój plik nie został dodany.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Zdjęcie o nazwie ". basename( $_FILES["fileToUpload"]["name"]). " zostało dodane.<br>";
    } else {
        echo "Podczas dodawania pliku wystąpił błąd.<br>";
    }
}
?>
    </div>
  </div>
</div>

<?php
require('../../shared/footer.php');
?>