<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_hall = mysqli_real_escape_string($link, $_POST['id_hall']);
        $hall_name = mysqli_real_escape_string($link, $_POST['hall_name']);
        $id_theater = mysqli_real_escape_string($link, $_POST['id_theater']);
        $id_theater = intval($id_theater);
        $capacity = mysqli_real_escape_string($link, $_POST['capacity']);

        //update
        if(isset($_POST['id_hall'])) {          
            $query =  
            "update 
                Hall 
            set  
                hall_name = '$hall_name', 
                id_theater = '$id_theater', 
                capacity = '$capacity'
            where
                id_hall='$id_hall';";
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
            $query = mysqli_query($link, "insert into Hall(hall_name, id_theater, capacity) values ('$hall_name', '$id_theater', '$capacity');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>