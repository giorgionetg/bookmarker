<?php

namespace KingFoo\BookmarkerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KingFoo\BookmarkerBundle\Entity\Bookmark;
use KingFoo\BookmarkerBundle\Entity\Tag;
use KingFoo\BookmarkerBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData implements FixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function load(ObjectManager $manager)
    {
        // Retrieve the Faker service
        $faker = $this->container->get('faker.generator');

        // Retrieve the password encoder
        $encoder = $this->container->get('security.encoder_factory')->getEncoder('KingFoo\BookmarkerBundle\Entity\User');

        // Create some tags
        $labels = array('doctrine', 'symfony', 'twig', 'kingfoo', 'training', 'yaml', 'php', 'mvc');
        $tags = array();
        foreach ($labels as $label) {
            $tag = new Tag($label);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        $manager->flush();

        $popular = array(
            'http://symfony.com/',
            'http://www.doctrine-project.org/',
            'http://twig.sensiolabs.org/',
            'http://www.google.com/'
        );

        for ($i = 0; $i < 20; $i++) {
            // Create a new user
            $user = new User();
            $user
                ->setUsername($faker->username)
                ->setEmail($faker->email)
                ->setPassword($encoder->encodePassword($user->getUsername(), $user->getSalt()));

            $manager->persist($user);

            // Add a popular bookmark
            $bookmark = new Bookmark();
            $bookmark
                ->setUser($user)
                ->setUrl($faker->randomElement($popular))
                ->setDescription($faker->paragraph)
                ->setCreatedAt($faker->dateTimeThisMonth);

            // Add some tags
            shuffle($tags);
            for ($j = 0; $j < rand(1, count($tags)); $j++) {
                $tag = $tags[($i + $j) % count($tags)];
                $bookmark->addTag($tag);
            }

            $manager->persist($bookmark);

            // Create some random bookmarks for every user
            for ($j = 0; $j < 50; $j++) {
                $bookmark = new Bookmark();
                $bookmark
                    ->setUser($user)
                    ->setUrl($faker->url)
                    ->setDescription($faker->paragraph)
                    ->setCreatedAt($faker->dateTimeThisYear);

                // Add some tags
                shuffle($tags);
                for ($k = 0; $k < rand(1, count($tags)); $k++) {
                    $tag = $tags[($i + $j + $k) % count($tags)];
                    $bookmark->addTag($tag);
                }

                $manager->persist($bookmark);
            }

            $manager->flush();
            $manager->detach($user);
            $manager->clear('KingFoo\BookmarkerBundle\Entity\Bookmark');
        }
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
