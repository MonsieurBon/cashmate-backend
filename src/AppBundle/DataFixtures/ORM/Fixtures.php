<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 15.09.17
 * Time: 06:43
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $account1 = new Account();
        $account1->setName("Haushalt");

        $account2 = new Account();
        $account2->setName("Sparen");

        $manager->persist($account1);
        $manager->persist($account2);
        $manager->flush();
    }
}