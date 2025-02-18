<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of MediatekformationControllerTest
 *
 * @author cdugu
 */
class MediatekformationControllerTest extends WebTestCase
{
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testTriFormation(){
    $client = static::createClient();

    // Accéder à la page de tri par titre descendant
    $crawler = $client->request('GET', '/formations/tri/title/DESC');

    // Vérifier que la requête a réussi
    $this->assertResponseIsSuccessful();

    // Récupérer le premier élément affiché dans la liste des formations
    $firstTitle = $crawler->filter('.text-info')->first()->text();

    // Vérifier que le premier titre est bien celui attendu
    $this->assertEquals("UML : Diagramme de paquetages", $firstTitle, "Le tri par titre DESC ne fonctionne pas correctement");
}

public function testTriPlaylist(){
    $client = static::createClient();
    $crawler = $client->request('GET', '/formations/tri/name/DESC/playlist');
    $this->assertResponseIsSuccessful();
    $firstName = $crawler->filter('td.text-left')->first()->text();
    $this->assertEquals("Visual Studio 2019 et C#", $firstName, "Le tri par nom DESC ne fonctionne pas correctement");
}

public function testTriDate(){
    $client = static::createClient();
    $crawler = $client->request('GET', '/formations/tri/publishedAt/DESC');
    $this->assertResponseIsSuccessful();
    $firstDate = $crawler->filter('td.text-center')->first()->text();
    $this->assertEquals("04/01/2021", $firstDate, "Le tri par date DESC ne fonctionne pas correctement");
}

    public function testFiltreFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        // simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'couleur'
        ]);
        // vérifie le nombre de lignes obtenues
        $this->assertCount(1, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
        $this->assertSelectorTextContains('h5', 'couleur');        
    }
    
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours'
        ]);
        $this->assertCount(44, $crawler->filter('td'));
        $this->assertSelectorTextContains('td', 'Cours');        
    }
    
    public function testLinkFormation(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        // Sélectionner le lien qui contient l'image et cliquer dessus
        $link = $crawler->filter('a[href="/formations/formation/4"]')->link();
        $client->click($link);

        // Vérifier si la page a bien été chargée
        $this->assertResponseIsSuccessful();

        // Vérifier l'URL après le clic
        $this->assertEquals('/formations/formation/4', $client->getRequest()->getRequestUri());
        
        // Vérifier un contenu
        $this->assertSelectorTextContains('h4', 'Refac');        

    }
}
