<?php

require_once("DBconnect.php");
$pdo = db_connect();
try{
 $sql= "CREATE TABLE sch_list (
    id         MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    pj_name    VARCHAR(50),
    contact_name VARCHAR(50),
    s_date        DATE,
    PRIMARY KEY (id)
    )";
 $res = $pdo->query($sql);
} catch(PDOException $e) {
  echo $e->getMessage();die();}

?>

