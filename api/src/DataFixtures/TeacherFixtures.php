<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Teacher;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class TeacherFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function getDependencies()
    {
        return [SchoolFixtures::class];
    }

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 4; $i++) { 
            $roleuser = new Role();
            $roleuser->setType("ROLE_USER");
            $manager->persist($roleuser);
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
    
            $user->setEmail("teacher$i@test.com")
                ->setPassword($hash)
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setCreatedAt(new \DateTime())
                ->addDbrole($roleuser)
                ->setRoles(['ROLE_TEACHER'])
                ;
    
                $teacher = new Teacher();
                $teacher->setUser($user);
    
                $this->addReference("teacher_$i" , $teacher);
    
            $manager->persist($user);
            $manager->persist($teacher);
        }

        
        $manager->flush();
    }

}
