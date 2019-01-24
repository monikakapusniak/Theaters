<?php
    include ($_SERVER["DOCUMENT_ROOT"] . "/database_connection.php");

    if (isset($_GET['logout'])){
        $q = mysqli_query($link, "delete from Session where id_cookie_session = '$_COOKIE[id]' and webbrowser = '$_SERVER[HTTP_USER_AGENT]';");	
        setcookie("id",0,time()-1);
        unset($_COOKIE['id']);
        
        $backToMyPage = $_SERVER['HTTP_REFERER'];
        if(isset($backToMyPage)) {
            header('Location: '.$backToMyPage);
        } else {
            header('Location: /index.php');
        }
        exit();
    }
?>


<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">TheaterWeb</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/theater/">Teatry</a></li>
                <li><a href="/spectacle/">Spektakle</a></li>
                <li><a href="/person/">Osoby</a></li>
                <!-- <li><a href="/admin/address/">Adresy</a></li> -->
            </ul>  

            <?php
            require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");

            if(checkIfLoggedIn()) {
                $isLoggedIn = true;
            } 
            else {
                $isLoggedIn = false;
            }
            if($isLoggedIn) {
                $login = getUserLogin();
                $user_type = getUserType();
                echo $user_type;
                if($user_type == "Admin") {
                    $adminDashboard = "<li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                        Panel administratora
                        </a>
                        <ul class='dropdown-menu'>
                            <li><a href='/admin/address'>Adresy</a></li>
                            <li><a href='/admin/cast'>Obsada</a></li>
                            <li><a href='/admin/character'>Postacie</a></li>
                            <li><a href='/admin/city'>Miasta</a></li>
                            <li><a href='/admin/crew'>Obsługa spektaklu</a></li>
                            <li><a href='/admin/hall'>Sale</a></li>
                            <li><a href='/admin/shows'>Występy</a></li>
                            <li><a href='/admin/theater'>Teatry</a></li>
                            <li><a href='/admin/spectacle'>Spektakle</a></li>
                            <li><a href='/admin/person'>Osoby</a></li>
                            <li><a href='/admin/person_info'>Info od osobach</a></li>
                            <li><a href='/admin/genre'>Gatunki spektakli</a></li>
                            <li><a href='/admin/crew_type'>Typy posad</a></li>
                            <li><a href='/admin/role_type'>Typy ról</a></li>
                            <li class=\"nav-divider\"></li>
                            <li><a href='/admin/log_table_dump_and_clear.php'>Wyczyść login logi</a></li>
                            <li><a href='/admin/images'>Dodaj zdjęcie</a></li>
                        </ul>
                    </li>";
                }
                else {
                    $adminDashboard = '';
                }
                echo 
                '<ul class=\'nav navbar-nav navbar-right\'>
                    '.$adminDashboard.'
                    <li><a href=\'?logout\'>'.$login.', wyloguj się <span class=\'glyphicon glyphicon-log-out\'></span></a></li>
                </ul>';

                
            }
            else {
                echo 
                "<ul class='nav navbar-nav navbar-right'>
                    <li><a href='/login.php'>Zaloguj się</a></li>
                </ul>";
            }
            ?>
            
            
            
        </div>
    </div>
</nav>
