<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 11/05/17
 * Time: 14:20
 *
 * @param $bdd
 *
 * @return bool
 */

function generate_database($bdd){
  $link = mysqli_connect($bdd['host'], $bdd['username'], $bdd['password']);
  if (!$link) {
    die('Not connected : ' . mysqli_error());
  }
  
  // make foo the current db
  $db_selected = mysqli_select_db($link,$bdd['name']);
  if (!$db_selected) {
    $sql = "CREATE DATABASE ".$bdd['name'];
    if ($link->query($sql) === TRUE) {
      $link->close();
    }
  }
  return true;
}