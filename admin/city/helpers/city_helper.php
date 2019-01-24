
<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_city = mysqli_real_escape_string($link, $_POST['id_city']);
        $city = mysqli_real_escape_string($link, $_POST['city']);

        //update
        if(isset($_POST['id_city'])) {          
            $query =  
            "update 
                City 
            set  
                city = '$city' 
            where
                id_city='$id_city';";

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
            $query = mysqli_query($link, "insert into City(city) values ('$city');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>