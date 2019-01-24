<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_person = mysqli_real_escape_string($link, $_POST['id_person']);
        $id_crew_type = mysqli_real_escape_string($link, $_POST['id_crew_type']);
        $id_show = mysqli_real_escape_string($link, $_POST['id_show']);

        //update
        if(isset($_POST['id_crew'])) {    
            $id_crew = mysqli_real_escape_string($link, $_POST['id_crew']);      
            $query =  
            "update 
                Crew 
            set  
                `id_crew_type` = '$id_crew_type',
                `id_person` = '$id_person',
                `id_show` = '$id_show'
            where
                id_crew='$id_crew';";
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
            $query = mysqli_query($link, "insert into Crew(`id_crew_type`, `id_person`, `id_show`) values ('$id_crew_type', '$id_person', '$id_show');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>