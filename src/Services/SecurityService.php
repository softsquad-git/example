<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityService
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserPasswordEncoderInterface $userPasswordEncoder
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @param string $password
     * @return User
     */
    public function register(User $user, string $password): User
    {
        $user->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param User $user
     * @param int $status
     * @return User
     */
    public function lockAccount(User $user, int $status): User
    {
        $user->setLocked($status);
        $this->entityManager->flush();

        return $user;
    }
}
