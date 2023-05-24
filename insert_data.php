<?php

use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration de la connexion à la base de données
$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'nom_de_votre_base_de_donnees',
    'user'     => 'votre_nom_d_utilisateur',
    'password' => 'votre_mot_de_passe',
];

// Configuration de Doctrine
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../src/Entity'], true);

// Création de l'entityManager
$entityManager = EntityManagerInterface::create($dbParams, $config);

// Données fictives pour les clients
$clientsData = [
    [
        'name' => 'Centre médical Gambetta',
        'adresse' => '27 rue Gambetta, Lyon 69003',
        'phone' => '04 72 61 72 72'
    ],
    [
        'name' => 'Hôpital Européen Georges Pompidou',
        'adresse' => '20 rue Leblanc, Paris 75015',
        'phone' => '01 56 09 20 00'
    ],
    [
        'name' => 'Centre médical Les Epinettes',
        'adresse' => '2 rue des Epinettes, Paris 75017',
        'phone' => '01 42 63 74 75'
    ],
    [
        'name' => 'Hôpital de la Timone',
        'adresse' => '264 rue Saint Pierre, Marseille 13385',
        'phone' => '04 91 38 00 00'
    ],
    [
        'name' => 'Centre médical du Maine',
        'adresse' => '32 avenue du Maine, Paris 75015',
        'phone' => '01 43 21 32 43'
    ]
];

// Création des entités Clients et leur persistance dans la base de données
foreach ($clientsData as $data) {
    $client = new Clients();
    $client->setName($data['name']);
    $client->setAdresse($data['adresse']);
    $client->setPhone($data['phone']);
    $entityManager->persist($client);
}

// Enregistrement des données dans la base de données
$entityManager->flush();
?>