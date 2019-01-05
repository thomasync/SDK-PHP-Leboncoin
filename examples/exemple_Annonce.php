<?php

require __DIR__.'/../vendor/autoload.php';

use Absmoca\Leboncoin;

$lbc = new Leboncoin();
$annonce = $lbc->getAnnonce(1529596885);
echo print_r($annonce, true);