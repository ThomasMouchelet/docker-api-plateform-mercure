<?php

namespace App\Controller;

use App\Entity\Delivrable;
use App\Repository\HomeworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class DelivrableController extends AbstractController
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
        $delivrable = new Delivrable();

        $user = $this->security->getUser();
        // dd($user);
        $student = $user->getStudent();
        $slug = $this->cleanString($user->getLastName()) . "-" . strtolower($user->getFirstName());

        

        $homework = $this->homeworkRepo->findOneBy(['id'=> $request->get('id')]);

        $delivrable
            ->setFile($file)
            ->setUploadedAt(new \DateTime())
            ->setStudent($student)
            ->setFileSlug($slug)
            ->setHomework($homework);
            ;

        

        $em->persist($delivrable);
        $em->flush();

        return $delivrable;
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
