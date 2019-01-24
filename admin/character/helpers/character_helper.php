<?php
    include '../../../database_connection.php';
    
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }
    
    if(isset($_POST)) {
        $id_spectacle = mysqli_real_escape_string($link, $_POST['id_spectacle']);
        $character_name = mysqli_real_escape_string($link, $_POST['character_name']);
        $id_character = mysqli_real_escape_string($link, $_POST['id_character']);

        //update
        if(isset($_POST['id_character'])) {          
            $query =  
            "update 
                Characters 
            set  
                character_name = '$character_name', 
                id_spectacle = '$id_spectacle' 
            where
                id_character='$id_character';";
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
            $query = mysqli_query($link, "insert into Characters(character_name, id_spectacle) values ('$character_name', '$id_spectacle');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>