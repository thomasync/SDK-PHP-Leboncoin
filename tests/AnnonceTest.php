<?php

use Absmoca\Leboncoin;
use Absmoca\Annonce;
use PHPUnit\Framework\TestCase;

class AnnonceTest extends TestCase {

    public function testConstructAnnonce() {
        $annonce = new Annonce();
        $this->assertIsObject($annonce);
    }

    public function testConstructAnnonceWithDatas() {
        $annonce = (new Leboncoin())->getAnnonce(1446302599);
        $this->assertIsObject($annonce);
    }

    public function testSetIdOnlyValid() {
        $this->expectException(Exception::class);
        $annonce = new Annonce();
        $annonce->setId(0);
    }

}