<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getDataFromJsonFile('/insert_data.json');
        foreach($data as $element) {
            $entite = new MonEntite();
            $entite->setPropriete1($element->propriete1);
            $entite->setPropriete2($element->propriete2);

            $manager->persist($entite);
        }

        $manager->flush();
    }

    private function getDataFromJsonFile(string $path)
    {
        $json = file_get_contents($path);
        return json_decode($json);
    }
}
