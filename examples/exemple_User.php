<?php

require __DIR__.'/../vendor/autoload.php';

use Absmoca\Leboncoin;

$lbc = new Leboncoin();
$status = $lbc->login('user@gmail.com', 'pasSworD1289');
if (!$status) {
    echo "L'authentification a échouée.\n";
    exit;
}
$user = $lbc->getUser();

echo '<pre>'.var_export($user, true).'</pre>';

echo '<pre>'.print_r($lbc->getMyAnnonces(), true).'</pre>';

$lbc->logout();