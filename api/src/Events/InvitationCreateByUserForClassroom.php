<?php

namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Invitation;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

class InvitationCreateByUserForClassroom implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForInvitation', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForInvitation(ViewEvent $event)
    {
        $invitation = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($invitation instanceof Invitation && $method === "POST") {
            $user = $this->security->getUser();
            $isTeacher = $user->getTeacher();
            if($isTeacher){
                $uuid = Uuid::v4();
                $uuidbase32 = $uuid->toBase32();
                $invitation
                    ->setOwner($user)
                    ->setUuid($uuidbase32)
                    ;
            }
        }
    }
}