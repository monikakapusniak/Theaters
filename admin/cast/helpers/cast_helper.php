<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();


    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_person = mysqli_real_escape_string($link, $_POST['id_person']);
        $id_role_type = mysqli_real_escape_string($link, $_POST['id_role_type']);
        $id_character = mysqli_real_escape_string($link, $_POST['id_character']);
        $id_show = mysqli_real_escape_string($link, $_POST['id_show']);

        //update
        if(isset($_POST['id_cast'])) {    
            $id_cast = mysqli_real_escape_string($link, $_POST['id_cast']);      
            $query =  
            "update 
                Casts 
            set  
                `id_character` = '$id_character', 
                `id_person` = '$id_person',
                `id_role_type` = '$id_role_type',
                `id_show` = '$id_show'
            where
                id_cast='$id_cast';";
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
            $query = mysqli_query($link, "insert into Casts(`id_character`, `id_person`, `id_role_type`, `id_show`) values ('$id_character', '$id_person', '$id_role_type', '$id_show');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>