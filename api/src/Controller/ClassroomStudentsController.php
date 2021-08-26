<?php

namespace App\Controller;

use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomStudentsController extends AbstractController
{
    public function  __invoke(Classroom $data, Request $request)
    {
        if ($request->isMethod('GET')) {
            
            $students = $data->getStudents();

            return $students;
        }
    }

}
