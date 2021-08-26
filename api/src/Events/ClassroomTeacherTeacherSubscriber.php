<?php

namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

class ClassroomTeacherTeacherSubscriber implements EventSubscriberInterface
{
    private $repository;
    private $security;

    public function __construct(ClassroomRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setTeacherForClassroom', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setTeacherForClassroom(ViewEvent $event)
    {
        $classroom = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($classroom instanceof Classroom && $method === "POST") {
            $teacher = $this->security->getUser()->getTeacher();
            $classroom->addTeacher($teacher);
        }
    }
}