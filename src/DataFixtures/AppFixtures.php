<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Post;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $passswordEncoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passswordEncoder)
    {
        $this->passswordEncoder = $passswordEncoder;
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->postLoad($manager);
        $this->commentLoad($manager);
    
    }
    public function commentLoad(ObjectManager $manager)
    { 
        for ($i= 0 ;$i <1000 ; $i++)
        {
            $comment = new Comment();
            $comment->setContent($this->faker->realText);
            $comment->setPublished(new \dateTime());

            $user = $this->getReference("user_admin_".rand(0,9));
            $post = $this->getReference("post_".rand(0,99));
            $comment->setAuthor($user);
            $comment->setPost($post);
    
            $manager->persist($comment);
        }
        $manager->flush();
    }
    public function postLoad(ObjectManager $manager)
    {
        for ($i= 0 ;$i <100 ; $i++)
        {
            $post = new Post();
            $post->setTitle($this->faker->sentence);
            $post->setSlug($this->faker->slug()) ;
            $post->setContent($this->faker->realText);
            $post->setPublished(new \dateTime());

            $user = $this->getReference("user_admin_".rand(0,9));
            $this->addReference("post_$i",$post);
            $post->setAuthor($user);
    
            $manager->persist($post);
        }
        $manager->flush();
    }
    public function loadUser(ObjectManager $manager)
    {
        for ($i= 0 ;$i <10 ; $i++)
        {
            $user = new User;
            $user->setUsername($this->faker->userName);
            $user->setName($this->faker->name);
            $user->setPassword($this->passswordEncoder->encodePassword($user,'secret123'));
            $user->setEmail($this->faker->email);
            $this->addReference("user_admin_$i", $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
