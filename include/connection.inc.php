<?php

$pdo = new PDO('mysql:host=localhost;dbname=diary; charset:utf8mb4' , 'root', 'deee', [
    PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION
]);

