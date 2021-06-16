<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return User
     */
    public function lockUnLockAccount(User $user): User
    {
        $user->setIsLocked($user->getIsLocked() == 1 ? 0 : 1);
        $this->em->flush();

        return $user;
    }
}
