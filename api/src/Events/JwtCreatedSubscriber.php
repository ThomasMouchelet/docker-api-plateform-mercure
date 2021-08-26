<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;

class JwtCreatedSubscriber
{
    private $hub; 

    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // 1. Récupérer l'utilisateur (pour avoir son firstName et lastName)
        $user = $event->getUser();

        // 2. Enrichir les data pour qu'elles contiennent ces données
        $data = $event->getData();
        $data['user_id'] = $user->getId();

        if($user->getTeacher()){
            $data['teacher_id'] = $user->getTeacher()->getId();
        }else if($user->getStudent()){
            $data['student_id'] = $user->getStudent()->getId();
        }

        $event->setData($data);
    }
}