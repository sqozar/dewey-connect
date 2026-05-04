<?php

namespace App\Tests\Entity;

use App\Entity\Trajet;
use App\Entity\Utilisateur;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class TrajetTest extends TestCase
{
    private Trajet $trajet;

    protected function setUp(): void
    {
        $this->trajet = new Trajet();
    }

    public function testCreationTrajetValide(): void
    {
        $utilisateur = new Utilisateur();
        $dateDepart = new \DateTime('+2 days');

        $this->trajet
            ->setPointDeDepart('Paris')
            ->setDestination('Lyon')
            ->setDateEtHeure($dateDepart)
            ->setSiegesLibres(3)
            ->setUtilisateur($utilisateur);

        $this->assertEquals('Paris', $this->trajet->getPointDeDepart());
        $this->assertEquals('Lyon', $this->trajet->getDestination());
        $this->assertEquals($dateDepart, $this->trajet->getDateEtHeure());
        $this->assertEquals(3, $this->trajet->getSiegesLibres());
        $this->assertEquals($utilisateur, $this->trajet->getUtilisateur());
    }

    public function testSettersAndGetters(): void
    {
        $dateDepart = new \DateTime('+5 days');
        $utilisateur = new Utilisateur();

        $this->trajet->setPointDeDepart('Marseille');
        $this->assertEquals('Marseille', $this->trajet->getPointDeDepart());

        $this->trajet->setDestination('Toulouse');
        $this->assertEquals('Toulouse', $this->trajet->getDestination());

        $this->trajet->setDateEtHeure($dateDepart);
        $this->assertEquals($dateDepart, $this->trajet->getDateEtHeure());

        $this->trajet->setSiegesLibres(5);
        $this->assertEquals(5, $this->trajet->getSiegesLibres());

        $this->trajet->setUtilisateur($utilisateur);
        $this->assertEquals($utilisateur, $this->trajet->getUtilisateur());
    }

    public function testSiegesLibresAuxValeursDifferentes(): void
    {
        $this->trajet->setSiegesLibres(1);
        $this->assertEquals(1, $this->trajet->getSiegesLibres());

        $this->trajet->setSiegesLibres(10);
        $this->assertEquals(10, $this->trajet->getSiegesLibres());
    }

    public function testAjoutReservation(): void
    {
        $reservation = new Reservation();
        $this->trajet->addReservation($reservation);

        $this->assertTrue($this->trajet->getReservations()->contains($reservation));
        $this->assertEquals($this->trajet, $reservation->getTrajet());
    }

    public function testAjoutMultiplesReservations(): void
    {
        $res1 = new Reservation();
        $res2 = new Reservation();

        $this->trajet->addReservation($res1);
        $this->trajet->addReservation($res2);

        $this->assertEquals(2, $this->trajet->getReservations()->count());
        $this->assertTrue($this->trajet->getReservations()->contains($res1));
        $this->assertTrue($this->trajet->getReservations()->contains($res2));
    }

    public function testSuppresionReservation(): void
    {
        $reservation = new Reservation();
        $this->trajet->addReservation($reservation);
        $this->trajet->removeReservation($reservation);

        $this->assertFalse($this->trajet->getReservations()->contains($reservation));
        $this->assertNull($reservation->getTrajet());
    }

    public function testUtilisateurAssociation(): void
    {
        $utilisateur = new Utilisateur();
        $this->trajet->setUtilisateur($utilisateur);

        $this->assertEquals($utilisateur, $this->trajet->getUtilisateur());
    }
}
