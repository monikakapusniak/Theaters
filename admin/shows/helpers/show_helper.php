<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }
    
    if(isset($_POST)) {
        $id_show = mysqli_real_escape_string($link, $_POST['id_show']);
        $id_spectacle = mysqli_real_escape_string($link, $_POST['id_spectacle']);
        $id_spectacle = intval($id_spectacle);
        $show_date = mysqli_real_escape_string($link, $_POST['show_date']);
        $id_hall = mysqli_real_escape_string($link, $_POST['id_hall']);
        $id_hall = intval($id_hall);

        $show_date = strtotime($show_date);
        $show_date = date('Y-m-d H:i', $show_date);

        //update
        if(isset($_POST['id_show'])) {          
            $query =  
            "update 
                Shows 
            set  
                id_spectacle = '$id_spectacle', 
                show_date = '$show_date',
                id_hall = '$id_hall' 
                
            where
                id_show='$id_show';";
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
            $query = mysqli_query($link, "insert into Shows(id_spectacle, show_date, id_hall) values ('$id_spectacle', '$show_date', '$id_hall');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }

?>
