<?php

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author cdugu
 */
class FormationTest extends TestCase {

    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2025-01-04 17:00:12"));
        $this->assertEquals("04/01/2025", $formation->getPublishedAtString());
    }
}
