<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use App\Services\TokenRandomizeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var TokenRandomizeService
     */
    private $tokenService;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenRandomizeService $tokenService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenService = $tokenService;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$name, $lastName, $email, $password, $roles]) {
            $user = new User();
            $user->setName($name);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setToken($this->tokenService->generateToken());

            $manager->persist($user);
        }

        $manager->flush();
    }



    private function getUserData()
    {
        return [
            ['Вася', 'Пупкин', 'vasya@mail.ru', 'qwerty', ['ROLE_ADMIN']],
            ['Петр', 'Бобров', 'petr@mail.ru', 'qwerty', ['ROLE_USER']],
            ['Сергей', 'Веселов', 'serj@mail.ru', 'qwerty', ['ROLE_USER']],
            ['Андрей', 'Петров', 'andrey@mail.ru', 'qwerty', ['ROLE_ADMIN']],
        ];
    }



}
