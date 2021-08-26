<?php

namespace App\DataFixtures;

use App\Entity\Inscription;
use App\Entity\Invitation;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Uid\Uuid;

class InvitationsFixtures extends Fixture implements DependentFixtureInterface
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getDependencies()
    {
        return [ClassroomsFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $classroom = $this->getReference("classroom_b1_cpd");
        $teacher = $this->getReference("teacher_1");
        $classroom->addTeacher($teacher);
        $owner = $teacher->getUser();

        $manager->persist($classroom);

        $uuid = Uuid::v4();
        $uuidbase32 = $uuid->toBase32();

        $invitation = new Invitation();
        $invitation
            ->setUuid($uuidbase32)
            ->setOwner($owner)
            ->setClassroom($classroom);

        $manager->persist($invitation);

        for($i=0; $i<20;$i++){
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");

            $user->setEmail($faker->email())
                ->setPassword($hash)
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setCreatedAt(new \DateTime());

            $student = new Student();
            $user->setStudent($student);

            $manager->persist($student);
            $manager->persist($user);

            $inscription = new Inscription();
            $inscription
                ->setUserRegister($user)
                ->setInvitation($invitation)
                ->setIsTeacher(true)
                ;

            $manager->persist($inscription);

        }

        $manager->flush();
    }
}
