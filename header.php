<?php
// iniciem la sessió de php
session_start();
// incloem la connexió amb la bbdd
include('connect.php');
// incloem la classe usuari
include('class/user.php');
$user = new User($dbConnection);
// fem la verificació de si l'usuari està identificat o no (boolean)
$userLoggedIn = $user->checkUserSession();
// ara podem condicionar certes coses preguntant si $userLoggedIn es 0 o 1
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Musicalitza - Amistats amb banda sonora</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Oswald|Lobster|Anton|Roboto|Roboto+Condensed|Mina|Yanone+Kaffeesatz|Arvo|Fjalla+One" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <header>
            <!-- MENU -->
            <nav class="navbar navbar-inverse">
                <div class="container">
                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>                        
                        </button>

                        <a class="navbar-brand" href="index.php">Musicalitza</a>
                    </div>

                    <div class="collapse navbar-collapse" id="myNavbar">

                        <ul class="nav navbar-nav">
                            <li>
                                <form class="form-inline">
                                    <input class="form-control mr-sm-2" type="search" placeholder="Cerca artistes, discos..." aria-label="Cerca">
                                    <button class="btn btn-link" type="submit"><i class="fa fa-search"></i>
                                    </button>
                                </form>
                            </li>
                            <li class="active"><a href="#">Artistes</a></li>
                            <li><a href="#">Albums</a></li>
                            <li><a href="#">Concerts</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="my-account.php"><span class="glyphicon glyphicon-user"></span> <?php if ($userLoggedIn) echo 'Hola ' . $_SESSION['nickname'];
else echo 'El meu compte' ?></a></li>
                            <?php if ($userLoggedIn) echo '<li><a href="action-logout.php"><span class="glyphicon glyphicon-off"></span> Tancar sessi&oacute;</a></li>'; ?>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End menu -->
