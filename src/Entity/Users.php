<?php

namespace App\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
#[ORM\Entity(repositoryClass: UsersRepository::class)]

class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $passwd = null;

    private $userRepository;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $id_role = null;

    public function __construct()
    {
        //$this->userRepository = $userRepository;
    }
    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

 
    public function setPassword(?string $passwd): self
    {
        $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
    
        return $this;
    }
    public function setUserRepository(UsersRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    public function checkCredentials($credentials): bool
    {
        $plainPassword = $credentials['passwd'];

        // Récupérer le mot de passe stocké dans la base de données pour l'utilisateur correspondant
        $user = $this->userRepository->findOneBy(['username' => $credentials['username']]);
        $hachedPassword = $user->getPassword();
        var_dump($credentials);
        var_dump($hachedPassword);
        var_dump(password_verify($plainPassword, $hachedPassword));
        
    
        // Comparer le mot de passe en texte clair fourni par l'utilisateur avec le mot de passe stocké dans la base de données
        return password_verify($plainPassword, $hachedPassword);

    }

    public function eraseCredentials(): void
    {
        // Remove any sensitive data or temporary data that should not be persisted.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    
    public function getRoles(): array
    {
        // Change this line to return the appropriate roles for your application.
        // You may want to store roles in the database or use a different approach.
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        // If you're using bcrypt, argon2i, or argon2id, the salt is already included in the password hash.
        // In this case, you can return null.
        return null;
    }
    public function getPassword(): ?string
    {
        return $this->passwd;
    }
    

        // Utiliser Field::new() pour définir le champ de l'entité
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('id_user')->setLabel('ID Utilisateur');
        yield TextField::new('username')->setLabel('Nom d\'utilisateur');
        yield TextField::new('passwd')->setLabel('Mot de pass');
    
    
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdRole(): ?int
    {
        return $this->id_role;
    }

    public function setIdRole(int $id_role): self
    {
        $this->id_role = $id_role;

        return $this;
    }
}
