<?php

namespace App\Controller;

use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ClassroomHomeworksController extends AbstractController
{
    public function  __invoke(Classroom $data, Request $request)
    {
        if ($request->isMethod('GET')) {
            
            $homeworks = $data->getHomeworks();

            return $homeworks;
        }
    }

}
