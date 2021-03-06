<!DOCTYPE html>
<html>
<head>
    <title>Planning poker</title>
    <meta charset="utf-8">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- send empty favicon fallback to prevent user's browser hitting the server for lots of favicon requests resulting in 404s -->
    <link rel="icon" href="data:;base64,=">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css" />
</head>
<body>
    <noscript style="font-size:20px;background-color:red;">
        javascript is requiered for this website to work please anable it.
    </noscript>
     <?php if (!Session::userIsLoggedIn()) { ?>
    <nav>
        <ul>
                <!-- for not logged in users -->
                <li<?php if (View::checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>login/index">Login</a>
                </li>
                <li<?php if (View::checkForActiveControllerAndAction($filename, "register/index")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>register/index">Register</a>
                </li>
           
        </ul>
    </nav>
    <?php } ?>
    <nav>
        <!-- my account -->
        <ul>
        <?php if (Session::userIsLoggedIn()) { ?>
            <li <?php if (View::checkForActiveController($filename, "user/index")) { echo ' class="active" '; } ?> >
                <a href="<?php echo Config::get('URL'); ?>user/index">My Account</a>
                    <li<?php if (View::checkForActiveController($filename, "user/editUsername")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>user/editusername">wijzig mijn gebruikersnaam</a>
                    </li>
                    <li<?php if (View::checkForActiveController($filename, "user/editUserEmail")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>user/edituseremail">wijzig mijn email</a>
                    </li>
                    <li<?php if (View::checkForActiveController($filename, "user/changePassword")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>user/changePassword">wijzig mijn paswoord</a>
                    </li>
                    <li<?php if (View::checkForActiveController($filename, "user/deleteUser")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>user/deleteUser">acount verwijderen</a>
                    </li>
                    <li>
                        <a href="<?php echo Config::get('URL'); ?>login/logout">Logout</a>
                    </li>
            <?php if (Session::get("user_account_type") == 7) { ?>
                <li <?php if (View::checkForActiveController($filename, "admin")) {
                    echo ' class="active" ';
                } ?> >
                    <a href="<?php echo Config::get('URL'); ?>admin/">Admin</a>
                </li>

            <?php } 
          } ?>
        </ul>
    </nav>