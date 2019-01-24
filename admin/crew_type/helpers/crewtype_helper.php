
<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $kind = mysqli_real_escape_string($link, $_POST['kind']);

        //update
        if(isset($_POST['id_crew_type'])) {  
            $id_crew_type = mysqli_real_escape_string($link, $_POST['id_crew_type']);
            $query =  
            "update 
                Crew_type 
            set  
                kind = '$kind'
            where
                id_crew_type='$id_crew_type';";

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
            $query = mysqli_query($link, "insert into Crew_type(kind) values ('$kind');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>