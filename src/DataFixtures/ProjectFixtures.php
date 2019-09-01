<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use App\Services\TokenRandomizeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->loadProjects($manager);
    }

    private function loadProjects($manager)
    {
        foreach ($this->getProjectsData() as [$name, $user_mail, $description, $inv_mail]){
            $project = new Project();
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $user_mail]);
            $user_inv = $manager->getRepository(User::class)->findOneBy(['email' => $inv_mail]);
            $project->setName($name);
            $project->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
            $project->setDescription($description);
            $project->setCreatedBy($user);
            $project->addInvitedUser($user_inv);
            $project->setToken($this->getToken());

            $manager->persist($project);
        }
        $manager->flush();
    }

    private function getProjectsData()
    {

        return [
            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru'],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru'],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru'],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru'],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru'],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru'],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru'],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru'],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru'],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru'],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru'],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru'],
        ];
    }



    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }

    public function getToken()
    {
        return substr(sha1(rand()), 0, 100);
    }
}
