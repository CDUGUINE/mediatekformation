<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use App\Entity\Playlist;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author cdugu
 */
class FormationValidationsTest extends KernelTestCase {
    public function getFormation(): Formation {
        $playlist = (new Playlist())->setName("playlist de test");

        return (new Formation())
            ->setPublishedAt(new DateTime("2025-01-04 17:00:12"))
            ->setTitle("ma formation")
            ->setVideoId("mapetiteidvideo")
            ->setPlaylist($playlist);
    }
    
    public function testValidDatePublication() {
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2025-01-04 17:00:12"));
        $this->assertErrors($formation, 0);
    }
    
    public function testNonValidDatePublication() {
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2026-01-04 17:00:12"));
        $this->assertErrors($formation, 1, "cette date en 2026 devrait échouer car postérieure à aujourd'hui");
    }
    
    public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error, $message);
    }
    
}
