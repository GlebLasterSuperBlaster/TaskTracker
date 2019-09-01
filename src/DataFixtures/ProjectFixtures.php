<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use App\Services\TokenRandomizeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var TokenRandomizeService
     */
    private $tokenService;

    /**
     * ProjectFixtures constructor.
     * @param TokenRandomizeService $tokenService
     */
    public function __construct(TokenRandomizeService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    public function load(ObjectManager $manager)
    {
        $this->loadProjects($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadProjects($manager)
    {
        $tokenService = new TokenRandomizeService();
        foreach ($this->getProjectsData() as [$name, $user_mail, $description, $inv_mail, $time]){
            /** @var Project $project */
            $project = new Project();
            $time = new \DateTime('now' . '-' . $time . 'seconds');
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $user_mail]);
            $user_inv = $manager->getRepository(User::class)->findOneBy(['email' => $inv_mail]);
            $project->setName($name);
            $project->setCreatedAtForFixtures(\DateTime::createFromFormat('Y-m-d H:i', (string) $time->format('Y-m-d H:i')));
            $project->setDescription($description);
            $project->setCreatedBy($user);
            $project->addInvitedUser($user_inv);
            $project->setToken($tokenService->generateToken());

            $manager->persist($project);
        }
        $manager->flush();
    }

    private function getProjectsData()
    {
        return [
            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru', 101232],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru', 14324],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru', 21232],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru', 4322],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru', 3313],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru', 123123],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru', 133124],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru', 1234523],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru', 54432523],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru', 1234123],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru', 2342123],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru', 453525],

            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru', 101232],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru', 14324],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru', 21232],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru', 4322],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru', 3313],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru', 123123],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru', 133124],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru', 1234523],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru', 54432523],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru', 1234123],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru', 2342123],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru', 453525],

            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru', 101232],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru', 14324],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru', 21232],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru', 4322],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru', 3313],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru', 123123],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru', 133124],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru', 1234523],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru', 54432523],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru', 1234123],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru', 2342123],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru', 453525],

            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru', 101232],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru', 14324],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru', 21232],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru', 4322],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru', 3313],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru', 123123],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru', 133124],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru', 1234523],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru', 54432523],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru', 1234123],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru', 2342123],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru', 453525],

            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru', 101232],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru', 14324],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru', 21232],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru', 4322],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru', 3313],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru', 123123],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru', 133124],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru', 1234523],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru', 54432523],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru', 1234123],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru', 2342123],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru', 453525],

            ['First Project1','vasya@mail.ru' ,"some Description", 'andrey@mail.ru', 101232],
            ['Task Tracker DRD 1','vasya@mail.ru',"some Description", 'petr@mail.ru', 14324],
            ['Some project MGM 1','vasya@mail.ru',"some Description",'serj@mail.ru', 21232],

            ['First Project Cooper 2','petr@mail.ru',"some Description",'vasya@mail.ru', 4322],
            ['Task Tracker Mister 2','petr@mail.ru',"some Description",'serj@mail.ru', 3313],
            ['Some project Evil Corp 2','petr@mail.ru',"some Description",'andrey@mail.ru', 123123],

            ['First Project Master of Puppets 3','serj@mail.ru',"some Description",'vasya@mail.ru', 133124],
            ['Task Tracker One 3','serj@mail.ru',"some Description",'andrey@mail.ru', 1234523],
            ['Some project Damage Inc 3','serj@mail.ru',"some Description",'petr@mail.ru', 54432523],

            ['First Project Unforgiven 4', 'andrey@mail.ru' ,"some Description",'serj@mail.ru', 1234123],
            ['Task Tracker Memory remains 4', 'andrey@mail.ru' ,"some Description",'vasya@mail.ru', 2342123],
            ['Some project Turn ThePage 4', 'andrey@mail.ru' ,"some Description",'petr@mail.ru', 453525],
        ];
    }



    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }
}
