<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();

        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
