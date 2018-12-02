<?php

require '../vendor/autoload.php';
use \Absmoca\Annonce;

$annonce = new Annonce(1529596885);

echo '<pre>' . print_r($annonce, true) . '</pre>';

?>