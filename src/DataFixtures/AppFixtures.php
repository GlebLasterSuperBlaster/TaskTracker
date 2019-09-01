<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadProjects($manager);

    }

    private function loadProjects($manager)
    {
        //foreach ($this->getProjectsData() as $name){
        //    $project = new Project();
        //    $project->setName($name);
        //    $project->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
        //    $project->setCategory(1);
        //    $project->setDescription("some Description");
        //    $project->setInvite(1);

        //    $manager->persist($project);
        //}

        //$manager->flush();

    }


}
