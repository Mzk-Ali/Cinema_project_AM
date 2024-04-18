<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" integrity="sha512-OQDNdI5rpnZ0BRhhJc+btbbtnxaj+LdQFeh0V9/igiEPDiWE2fG+ZsXl0JEH+bjXKPJ3zcXqNyP4/F/NegVdZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="public/css/style.css">
    <title><?= $titre ?></title>
</head>
<body>
    <header class="header">
        <nav class="nav_container">
            <div class="nav_home">
                <a href="index.php?action=home_view">HOME</a>
            </div>
            <div class="nav_menu">
                <ul class="nav_menu_list">
                    <li class="nav_item">
                        <a href="index.php?action=film_view&genre=Action">FILM</a>
                    </li>
                    <li class="nav_item">
                        <a href="index.php?action=realisateur_view">REALISATEUR</a>
                    </li>
                    <li class="nav_item">
                        <a href="index.php?action=acteur_view">ACTEUR</a>
                    </li>
                </ul>
            </div>
            <div class="nav_add">
                <a href="index.php?action=add_view">
                    <i class="ri-user-settings-fill"></i>
                </a>
            </div>
        </nav>

    </header>
    <main>
        <h1><?= $titre ?></h1>
        <?= $contenu ?>
    </main>

    <footer>
        <div class="container_footer">
            <div class="title_footer">
                <a href=""><p>WIKI</p>
                <p class="title_cine">CINE</p></a>
            </div>
            <div class="main_footer">
                <a href=""><p>Mentions Légales</p></a>
                <a href=""><p>Gestion des cookies</p></a>
                <a href=""><p>Plan du Site</p></a>
            </div>
            <div class="logo_footer">
                <a href=""><i class="ri-facebook-circle-fill"></i></a>
                <a href=""><i class="ri-instagram-fill"></i></a>
                <a href=""><i class="ri-twitter-x-fill"></i></a>
            </div>
        </div>
    </footer>
    
</body>
    <script src="public/js/main.js"></script>
</html>