<?php

use Absmoca\Leboncoin;
use PHPUnit\Framework\TestCase;

class LeboncoinTest extends TestCase
{
    public function testGetAnnonces() {
        $lbc = new Leboncoin();
        $params = array(
            "query" => ""
        );
        $annonces = $lbc->getAnnonces($params);
        print_r($annonces);
        $this->assertTrue(count($annonces->annonces) > 0);
    }

    public function testGetAnnonce() {
        $id = 1446302599;
        $annonce = (new Leboncoin())->getAnnonce($id);
        $this->assertEquals($id, $annonce->getId());
    }

    public function testGetAnnonceError() {
        $id = "error";
        $annonce = (new Leboncoin())->getAnnonce($id);
        $this->assertIsNotObject($annonce);
    }

    public function testGetAnnoncesOnlyParticuliers() {
        $lbc = new Leboncoin();
        $params = array(
            "query" => "",
            "professionnels" => false
        );
        $annonces = $lbc->getAnnonces($params);
        $finds = 0;
        /* @var $annonce \Absmoca\Annonce */
        foreach ($annonces->annonces as $annonce) {
            if($annonce->getOwner()["type"] == "pro") $finds =+ 1;
        }
        $this->assertEquals(0, $finds);
    }

    public function testGetAnnoncesOnlyPro() {
        $lbc = new Leboncoin();
        $params = array(
            "query" => "",
            "particuliers" => false
        );
        $annonces = $lbc->getAnnonces($params);
        $finds = 0;
        /* @var $annonce \Absmoca\Annonce */
        foreach ($annonces->annonces as $annonce) {
            if($annonce->getOwner()["type"] == "private") $finds =+ 1;
        }
        $this->assertEquals(0, $finds);
    }

    public function testSearchCategory() {
        $lbc = new Leboncoin();
        $categorie = $lbc->searchCategory("Voiture");
        $this->assertObjectHasAttribute("id", $categorie);
    }

    public function testSearchLocation() {
        $lbc = new Leboncoin();
        $locations = $lbc->searchLocation("Beziers");
        $this->assertTrue(count($locations) > 0);
    }

    public function testSearchLocationPostal() {
        $lbc = new Leboncoin();
        $locations = $lbc->searchLocation(34500);
        $this->assertTrue(count($locations) > 0);
    }

    public function testSearchLocationPrecis() {
        $lbc = new Leboncoin();
        $location = $lbc->searchLocation("Beziers", true);
        $this->assertObjectHasAttribute("city", $location);
    }

    public function testSearchLocationPrecisPostal() {
        $lbc = new Leboncoin();
        $location = $lbc->searchLocation(34500, true);
        $this->assertObjectHasAttribute("city", $location);
    }

}