<?php

namespace App\DataFixtures;

use App\Entity\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getStatusData() as [$id, $title]) {
            $status = new TaskStatus();
            $status->setId($id);
            $status->setTitle($title);
            $manager->persist($status);
        }

        $manager->flush();
    }

    private function getStatusData()
    {
        return [
            [1, TaskStatus::NEW],
            [2, TaskStatus::IN_PROGRESS],
            [3, TaskStatus::DONE],
        ];
    }
}
