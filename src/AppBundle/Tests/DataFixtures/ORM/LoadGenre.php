<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Genre;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadGenres
 *
 * @author mjoyce
 */
class LoadGenre extends AbstractFixture {

    public function load(ObjectManager $manager) {
        $genre1 = new Genre();
        $genre1->setName("fiction");
        $genre1->setLabel("Fiction");
        $this->setReference('genre.1', $genre1);
        $manager->persist($genre1);

        $genre2 = new Genre();
        $genre2->setName("non-fiction");
        $genre2->setLabel("Non fiction");
        $this->setReference('genre.2', $genre2);
        $manager->persist($genre2);

        $manager->flush();
    }

}
