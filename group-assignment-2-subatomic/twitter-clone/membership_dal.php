<?php

  require_once 'functions.php';


  function doLogin($uname, $pwd, $db) {
      $username = $uname;
      $password = $pwd;

      // Query the database with the login values
      $authQuery = "select * from users where pwd='$password' and uname='$username' ";
      $response = $db->query($authQuery);
    return $response;
  }

  function registerNewUser($uname, $pwd, $db) {
        $username = $uname;
        $password = $pwd;
        // Query the database with the login values to create a new user into the database
        $newUserQuery = "insert into users(uname, pwd) values('$username', '$password');";
    $response = $db->query($newUserQuery);
    return $response;
  }

  ?>

 