<?php 
  require('shared/header.php');
  require('shared/navbar.php');
  include 'database_connection.php';
?>



<div class="container">  
  <div class="panel panel-info">
    <div class="panel-body">

    <!-- jak to dziala -->
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 ">
                <div class="alert alert-info text-center" role="alert">
                    <strong>Theater Web - WYRAŹ SWOJĄ OPINIĘ</strong>
                </div>
                <div class="col-md-8">
                    <h2 class="mt-4">Jak to działa?</h2>
                    <p>Jest to system o tematyce teatrów na terenie Polski w postaci aplikacji internetowej.</p>
                    <p>Głównym jego zadaniem jest efektywne wyszukiwanie teatrów, spektakli oraz aktorów teatralnych.</p>
                    <p> Gromadzi opinie użytkowników na temat aktorów, spektakli czy teatrów.</p> 
                </div>
                <div class="col-md-4">
                    <img src='/media/default/theater_masks.png' class="img-responsive" alt='Image not found' onerror="this.src='/media/default/default_spectacle.png';">
                </div>
            </div>
        </div>
    </div><br/>

    <!-- nadchodzace premiery -->
    <?php
    $query = mysqli_query($link, "CALL showUpcomingPremieresSpectacles('5')");
    $counter = 0;
    $numResults = mysqli_num_rows($query);
    if($numResults > 0) {
        echo "<div class=\"row\">
            <div class=\"col-md-12\"><div class=\"col-md-12\">
        <div class=\"alert alert-info text-center\" role=\"alert\">
        <strong>Najbliższe premiery spektakli ($numResults):</strong>
        </div></div>
        </div>
        </div>";
    }
    ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="carousel slide" data-ride="carousel" id="quote-carousel">
                <!-- Bottom Carousel Indicators -->
                <ol class="carousel-indicators">
                    <?php
                        while ($counter < $numResults){
                            if($counter == 0) {
                                echo 
                                "<li data-target=\"#quote-carousel\" data-slide-to=\"0\" class=\"active\"></li>";
                            }
                            else {
                                echo
                                "<li data-target=\"#quote-carousel\" data-slide-to=\"$counter\"></li>";
                            }
                            $counter++;
                        }  
                    ?>
                </ol>
                <div class="carousel-inner">
                <?php
                $counter = 0;
                while ($row = mysqli_fetch_assoc($query)){
                    $row['photo_name'] = htmlspecialchars($row['photo_name']);
                    $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
                    $row['date_of_premiere_formatted'] = htmlspecialchars($row['date_of_premiere_formatted']);
                    $row['description'] = htmlspecialchars($row['description']);
                    if($counter == 0) {
                        echo 
                        "<!-- Quote 1 -->
                            <div class=\"item active\">
                                <div class=\"row\">
                                <div class=\"col-md-3 text-center\">
                                    <img class=\"img-circle\" src='$row[photo_name]' style=\"width: 100px;height:100px;\" alt='Image not found' onerror=\"this.src='/media/default/default_spectacle.png';\">
                                </div>
                                <div class=\"col-md-9\">
                                    <a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong><h2>$row[spectacle_name]</h2></strong></a>
                                    <h4>Data premiery: $row[date_of_premiere_formatted]</h4>
                                    <p>$row[description]</p>
                                </div>
                                </div>
                            </div>";
                    }
                    else {
                        echo
                        "<div class=\"item\">
                            <div class=\"row\">
                            <div class=\"col-md-3 text-center\">
                                <img class=\"img-circle\" src='$row[photo_name]' style=\"width: 100px;height:100px;\" alt='Image not found' onerror=\"this.src='/media/default/default_spectacle.png';\">
                            </div>
                            <div class=\"col-md-9\">
                                <a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong><h2>$row[spectacle_name]</h2></strong></a>
                                <h4>Data premiery: $row[date_of_premiere_formatted]</h4>
                                <p>$row[description]</p>
                            </div>
                            </div>
                        </div>";
                    }
                    $counter = $counter + 1;
                    }
                ?>   
                </div>
            
            <!-- Carousel Buttons Next/Prev -->
            <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
            <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
            </div>                          
        </div>
    </div><br/>

    <!-- rozwoj -->
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    <strong>Theater Web - WYRAŹ SWOJĄ OPINIĘ</strong>
                </div>
                <div class="col-md-4">
                    <img src='/media/default/theater_building.png' class="img-responsive" alt='Image not found' onerror="this.src='/media/default/default_spectacle.png';">
                </div>
                <div class="col-md-8">
                <h2 class="mt-4">Rozwój</h2>
                    <p>System zachęca użytkowników do wyrażenia własnego
                        zdania, co umożliwia także innym osobom, gościom
                        strony, rozeznanie się, który ze spektakli mógłby ich
                        zainteresować.
                    </p>
                </div>
            </div>
        </div>
    </div><br/>

    <!-- najpopularniejsze spektakle -->
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    <?php
                    include 'database_connection.php';
                    $query = mysqli_query($link, "CALL showMostPopularSpectacles('5')");
                    $counter = 0;
                    $numResults = mysqli_num_rows($query);
                    //nadchodzace premiery
                    if($numResults > 0) {
                        echo "<strong>Najpopularniejsze spektakle ($numResults):</strong>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-12'>
            <div class="carousel slide" data-ride="carousel" id="quote-carousel2">
                <!-- Bottom Carousel Indicators -->
                <ol class="carousel-indicators">
                    <?php
                        while ($counter < $numResults){
                            if($counter == 0) {
                                echo 
                                "<li data-target=\"#quote-carousel2\" data-slide-to=\"0\" class=\"active\"></li>";
                            }
                            else {
                                echo
                                "<li data-target=\"#quote-carousel2\" data-slide-to=\"$counter\"></li>";
                            }
                            $counter++;
                        }  
                    ?>
                </ol>
                <div class="carousel-inner">
                <?php
                $counter = 0;
                while ($row = mysqli_fetch_assoc($query)){
                    $row['photo_name'] = htmlspecialchars($row['photo_name']);
                    $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
                    $row['number_of_ratings'] = htmlspecialchars($row['number_of_ratings']);
                    $row['rating'] = htmlspecialchars($row['rating']);
                    $row['description'] = htmlspecialchars($row['description']);
                    if($counter == 0) {
                        echo 
                        "<!-- Quote 1 -->
                            <div class=\"item active\">
                                <div class=\"row\">
                                <div class=\"col-md-3 text-center\">
                                    <img class=\"img-circle\" src='$row[photo_name]' style=\"width: 100px;height:100px;\" alt='Image not found' onerror=\"this.src='/media/default/default_spectacle.png';\">
                                </div>
                                <div class=\"col-md-9\">
                                    <a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong><h2>$row[spectacle_name]</h2></strong></a>
                                    <h4>Ocena: $row[rating] (Liczba oceniających: $row[number_of_ratings])</h4>
                                    <p>$row[description]</p>
                                </div>
                                </div>
                            </div>";
                    }
                    else {
                        echo
                        "<div class=\"item\">
                            <div class=\"row\">
                            <div class=\"col-md-3 text-center\">
                                <img class=\"img-circle\" src='$row[photo_name]' style=\"width: 100px;height:100px;\" alt='Image not found' onerror=\"this.src='/media/default/default_spectacle.png';\">
                            </div>
                            <div class=\"col-md-9\">
                                <a href=\"/spectacle/details.php?id=$row[id_spectacle]\"><strong><h2>$row[spectacle_name]</h2></strong></a>
                                <h4>Ocena: $row[rating] (Liczba oceniających: $row[number_of_ratings])</h4>
                                <p>$row[description]</p>
                            </div>
                            </div>
                        </div>";
                    }
                    $counter = $counter + 1;
                    }
                ?>   
                </div>
            <!-- Carousel Buttons Next/Prev -->
            <a data-slide="prev" href="#quote-carousel2" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
            <a data-slide="next" href="#quote-carousel2" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
            </div>                          
        </div>
    </div><br/>
    
    </div>
  </div>
</div>


<?php 
  require('shared/footer.php');
?>

<script>
$(document).ready(function(ev){
    $('#quote-carousel').carousel({
    pause: true,
    interval: 5000,
  });
  $('#quote-carousel2').carousel({
    pause: true,
    interval: 5000,
  });
});

</script>