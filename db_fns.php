<?php

function db_connect() {
   $result = new mysqli('localhost', "developer", "123456", "bbs");
   if (!$result) {
     throw new Exception('Could not connect to database server');
   } else {
     return $result;
   }
}

?>
