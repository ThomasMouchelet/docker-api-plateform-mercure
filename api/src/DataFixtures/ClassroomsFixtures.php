<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\SchoolFixtures;
use App\Entity\Homework;
use App\Entity\Invitation;
use App\Entity\Role;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

class ClassroomsFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getDependencies()
    {
        return [SchoolFixtures::class, TeacherFixtures::class];
    }

    const CLASSROOMS = [
        [
            'name' => 'B1_CPD',
            'school' => 'school_esd'
        ],
        [
            'name' => 'B2_CPD',
            'school' => 'school_esd'
        ],
        [
            'name' => 'B3_CPD',
            'school' => 'school_esd'
        ],
        [
            'name' => 'INFO_B3C',
            'school' => 'school_ynov'
        ],
        [
            'name' => 'Webdesign',
            'school' => 'school_mjm'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        foreach (self::CLASSROOMS as $value) {
            $classroom = new Classroom();
            $classroom->setName($value['name'])
                    ->setSchool($this->getReference($value['school']))                    
                    ;

            for ($i=0; $i < rand(1,3); $i++) { 
                $teacher = $this->getReference("teacher_" . rand(0,3));
                $classroom->addTeacher($teacher);
            }

            $this->addReference('classroom_' . strtolower($value['name']) , $classroom);

            $roleuser = new Role();
            $roleuser->setType("ROLE_USER");
            $manager->persist($roleuser);

            for ($u = 0; $u < rand(8,25); $u++) {
                $user = new User();
                $hash = $this->encoder->encodePassword($user, "password");

                $user->setEmail($faker->email())
                    ->setPassword($hash)
                    ->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setCreatedAt(new \DateTime())
                    ->addDbrole($roleuser)
                    ->setRoles(['ROLE_STUDENT'])
                ;

                $student = new Student();
                $student->addClassroom($classroom);
                $user->setStudent($student);

                $manager->persist($user);
            }

            for($i = 0; $i < 5; $i++) {
                $subject = new Subject();
                $subject->setName("Matière $i");

                for ($j=0; $j < rand(1, 5); $j++) { 
                    $homework = new Homework();
                    $subjectName = $subject->getName();
                    $homework->setName("Homework $subjectName : Devoir $j")
                             ->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ac viverra metus. Fusce tellus urna, facilisis ac dui efficitur, placerat suscipit odio. Nam in ex et odio iaculis dictum sit amet quis nunc. ")
                             ->setClassroom($classroom)
                             ->setSubject($subject)
                             ;

                    $uuid = Uuid::v4();
                    $uuidbase32 = $uuid->toBase32();
                    $classroom->setInvitationCode($uuidbase32);

                    $teachers = $classroom->getTeachers();
                    $teacherHomework = $teachers[rand(0, count($teachers) - 1)];
                    $homework->setTeacher($teacherHomework);
                            
                    $manager->persist($homework);
                }

                $manager->persist($subject);
            }

            $manager->persist($classroom);
        }
        
        $manager->flush();
    }
}
