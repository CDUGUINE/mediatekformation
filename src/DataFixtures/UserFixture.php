<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Assure le hachage du mot de passe
 * @author cdugu
 */
class UserFixture extends Fixture
{
    /**
     * @var type
     */
    private $passwordHasher;
    
    /**
     * Constructeur
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }
    
    /**
     * Définit l'utilisateur admin et hache son mot de passe
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("admin");
        $plaintextPassword = "Form@tion$";
        $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
