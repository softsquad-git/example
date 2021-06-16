<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->userPasswordEncoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setName('User name '.$i)
                ->setEmail('user-example-'.$i.'@example.org')
                ->setPassword($this->userPasswordEncoder->encodePassword($user, 'start123'))
                ->setIsLocked(false);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
