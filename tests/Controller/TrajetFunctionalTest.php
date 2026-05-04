<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrajetFunctionalTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testAccueilPageLoads(): void
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testFormulaireTrajetPresent(): void
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        
        // Vérifie les champs du formulaire
        $this->assertSelectorExists('input[name="trajet[point_de_depart]"]');
        $this->assertSelectorExists('input[name="trajet[destination]"]');
        $this->assertSelectorExists('input[name="trajet[date_et_heure]"]');
        $this->assertSelectorExists('input[name="trajet[sieges_libres]"]');
    }

    public function testAccesListeTrajetsRequiertAuthentification(): void
    {
        $this->client->request('GET', '/profil/trajets');
        $this->assertResponseRedirects('/connexion');
    }

    public function testAccesProfilRequiertAuthentification(): void
    {
        $this->client->request('GET', '/profil');
        $this->assertResponseRedirects('/connexion');
    }

    public function testPageConnexionLoads(): void
    {
        $this->client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful();
    }
}
