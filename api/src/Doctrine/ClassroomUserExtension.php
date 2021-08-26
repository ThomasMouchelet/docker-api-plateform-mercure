<?php

namespace App\Doctrine;

use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Classroom;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;

class ClassroomUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $auth;

    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->auth = $checker;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $user = $this->security->getUser();

        if (
            ($resourceClass === Classroom::class)
            &&
            $user instanceof User
        ) {
            $rootAlias = $queryBuilder->getRootAliases()[0];

            $teacher = $user->getTeacher();
            $student = $user->getStudent();

            if(isset($teacher)){
                $queryBuilder->innerJoin("$rootAlias.teachers", "t");
                $queryBuilder->where("t = :teacher")
                    ->setParameter('teacher', $teacher);
            }else {
                $queryBuilder->innerJoin("$rootAlias.students", "s");
                $queryBuilder->where("s = :student")
                    ->setParameter('student', $student);
            }
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        // $this->addWhere($queryBuilder, $resourceClass);
    }
}