<?php

require __DIR__.'/../vendor/autoload.php';

use Absmoca\Leboncoin;

$lbc = new Leboncoin();
try {
    $annonce = $lbc->getAnnonce(17560277732);
    echo print_r($annonce, true);
} catch(Exception $ex) {
    echo 'Aucune annonce';
}