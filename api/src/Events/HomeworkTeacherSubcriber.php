<?php

namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Homework;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use App\Repository\HomeworkRepository;
use Symfony\Component\Security\Core\Security;

class HomeworkTeacherSubcriber implements EventSubscriberInterface
{
    private $repository;
    private $security;

    public function __construct(HomeworkRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForHomework', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForHomework(ViewEvent $event)
    {
        $homework = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($homework instanceof Homework && $method === "POST") {
            // $homework->addTeacher($this->security->getUser());
            $teacher = $this->security->getUser()->getTeacher();
            $homework->setTeacher($teacher);
            if (empty($homework->getCreatedAt())) {
                $homework->setCreatedAt(new \DateTime());
            }
        }
    }
}