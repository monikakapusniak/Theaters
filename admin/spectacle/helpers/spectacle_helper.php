<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }
    
    if(isset($_POST)) {
        $spectacle_name = mysqli_real_escape_string($link, $_POST['spectacle_name']);
        $date_of_premiere = mysqli_real_escape_string($link, $_POST['date_of_premiere']);
        $duration = mysqli_real_escape_string($link, $_POST['duration']);
        $id_genre = mysqli_real_escape_string($link, $_POST['id_genre']);
        $description = mysqli_real_escape_string($link, $_POST['description']);
        $photo_name = mysqli_real_escape_string($link, $_POST['photo_name']);


        $date_of_premiere = strtotime($date_of_premiere);
        $date_of_premiere = date('Y-m-d H:i', $date_of_premiere);

        //update
        if(isset($_POST['id_spectacle'])) {         
            $id_spectacle = mysqli_real_escape_string($link, $_POST['id_spectacle']);
            $id_spectacle = intval($id_spectacle); 
            $query =  
            "update 
                Spectacle 
            set  
                spectacle_name = '$spectacle_name', 
                date_of_premiere = '$date_of_premiere',
                `id_genre` = '$id_genre',
                description = '$description',
                photo_name = '$photo_name',
                duration = '$duration'
            where
                id_spectacle='$id_spectacle';";

            $result = mysqli_query($link, $query);
    
            if($result) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }

        //create
        else {          
            $query = mysqli_query($link, "insert into Spectacle(`spectacle_name`, `date_of_premiere`, `id_genre`, `description`, `photo_name`, `duration`) values ('$spectacle_name', '$date_of_premiere', '$id_genre', '$description', '$photo_name', '$duration');");

            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }

?>
