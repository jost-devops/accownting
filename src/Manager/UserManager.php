<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\CompanyDTO;
use App\DTO\UserDTO;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }



    public function add(UserDTO $userDTO, User $actingUser): User
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $userDTO->password));
        $user->setName($userDTO->name);
        $user->setCreated(new \DateTime());
        $user->setCreatedBy($actingUser);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getEdit(User $user): UserDTO
    {
        $userDTO = new UserDTO();
        $userDTO->email = $user->getEmail();
        $userDTO->name = $user->getName();

        return $userDTO;
    }

    public function edit(User $user, UserDTO $userDTO, User $actingUser): User
    {
        $user->setEmail($userDTO->email);

        if ($userDTO->password !== null) {
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $userDTO->password));
        }

        $user->setName($userDTO->name);
        $user->setUpdated(new \DateTime());
        $user->setUpdatedBy($actingUser);

        $this->entityManager->flush();

        return $user;
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
