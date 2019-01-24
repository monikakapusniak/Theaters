
<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $genre = mysqli_real_escape_string($link, $_POST['genre']);

        //update
        if(isset($_POST['id_genre'])) {  
            $id_genre = mysqli_real_escape_string($link, $_POST['id_genre']);
            $query =  
            "update 
                Genre 
            set  
                genre = '$genre'
            where
                id_genre='$id_genre';";

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
            $query = mysqli_query($link, "insert into Genre(genre) values ('$genre');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>