<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_theater = mysqli_real_escape_string($link, $_POST['id_theater']);
        $theater_name = mysqli_real_escape_string($link, $_POST['theater_name']);
        $id_address = mysqli_real_escape_string($link, $_POST['id_address']);
        $id_address = intval($id_address);
        $photo_name = mysqli_real_escape_string($link, $_POST['photo_name']);

        //update
        if(isset($_POST['id_theater'])) {          
            $query =  
            "update 
                Theater 
            set  
                theater_name = '$theater_name', 
                id_address = '$id_address', 
                photo_name = '$photo_name'
            where
                id_theater='$id_theater';";
                
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
            $query = mysqli_query($link, "insert into Theater(theater_name, id_address, photo_name) values ('$theater_name', '$id_address', '$photo_name');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>