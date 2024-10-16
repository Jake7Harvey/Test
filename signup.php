<?php

if (empty($_POST["name"])){
    die("Name Needed");
}


if ( ! filter_var($_POST["email"] , FILTER_VALIDATE_EMAIL)){
    die("Valid email is needed");
}





print_r($_POST);