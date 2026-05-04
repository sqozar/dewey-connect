<?php

namespace App\Tests\Form;

use App\Entity\Trajet;
use App\Form\TrajetType;
use Symfony\Component\Form\Test\TypeTestCase;

class TrajetTypeTest extends TypeTestCase
{
    public function testFormRendersWithCorrectFields(): void
    {
        $form = $this->factory->create(TrajetType::class);

        $this->assertTrue($form->has('point_de_depart'));
        $this->assertTrue($form->has('destination'));
        $this->assertTrue($form->has('date_et_heure'));
        $this->assertTrue($form->has('sieges_libres'));
    }

    public function testFormCreationWithEntity(): void
    {
        $trajet = new Trajet();
        $form = $this->factory->create(TrajetType::class, $trajet);

        $this->assertInstanceOf(Trajet::class, $form->getData());
    }
}
