<?php

namespace App\Controller;

use App\Entity\HomeworkFile;
use App\Repository\HomeworkRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class HomeworkFileController extends AbstractController
{
    private $security;
    private $homeworkRepo;

    public function __construct(Security $security, HomeworkRepository $homeworkRepo)
    {
        $this->security = $security;
        $this->homeworkRepo = $homeworkRepo;
    }

    public function  __invoke(Request $request, EntityManagerInterface $em)
    {
        $file = $request->files->get('file');
        $homeworkFile = new HomeworkFile();
        
        $user = $this->security->getUser();
        $homework = $this->homeworkRepo->findOneBy(['id'=> $request->get('id')]);
        
        $teacher = $user->getTeacher();

        $date = new DateTime("now", new \DateTimeZone('Europe/Paris'));
        $formatDate = $date->format("d-m-Y-H-i-s");
  
        $slug = $this->cleanString($user->getLastName()) . "-" . strtolower($user->getFirstName() . "-" . $homework->getId() . "-" . $formatDate);
        
        $homeworkFile
            ->setFile($file)
            ->setUploadedAt(new \DateTime())
            ->setTeacher($teacher)
            ->setFileSlug($slug)
            ->setHomework($homework);
            ;
            
        $em->persist($homeworkFile);
        $em->flush();

        return $homeworkFile;
    }

    protected function cleanString($text) {
        $text = strtolower($text);
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[óòôõºö]/u'   =>   'o',
            '/[úùûü]/u'     =>   'u', 
            '/ç/'           =>   'c',
            '/ñ/'           =>   'n',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   '', // Literally a single quote
            '/[“”«»„]/u'    =>   '', // Double quote
            '/ /'           =>   '_',
            '/:/'           =>   '',
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

}
