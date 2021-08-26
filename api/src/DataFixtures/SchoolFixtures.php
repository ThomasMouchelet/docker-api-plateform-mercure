<?php

namespace App\DataFixtures;

use App\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SchoolFixtures extends Fixture
{
    const SCHOOLS = [
        [
            'name' => 'ESD',
            'address' => '11 Place de la Ferme Richemont, 33000 Bordeaux',
            'city' => 'Bordeaux',
            'postalCode' => 33000
        ],
        [
            'name' => 'MJM',
            'address' => '124 Rue du Dr Albert Barraud, 33000 Bordeaux',
            'city' => 'Bordeaux',
            'postalCode' => 33000
        ],
        [
            'name' => 'YNOV',
            'address' => '89 Quai des Chartrons, 33300 Bordeaux',
            'city' => 'Paris',
            'postalCode' => 75000
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::SCHOOLS as $value) {
            $school = new School();
            $school->setName($value['name'])
                   ->setAddress($value['address'])
                   ->setCity($value["city"])
                   ->setPostalCode($value["postalCode"])  
                ;
            
            $this->addReference('school_' . strtolower($value['name']) , $school);
            $manager->persist($school);
        }

        $manager->flush();
    }
}
