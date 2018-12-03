<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\UnitDTO;
use App\Entity\Unit;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UnitManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(UnitDTO $unitDTO, User $user): Unit
    {
        $unit = new Unit();
        $unit->setName($unitDTO->name);
        $unit->setAllIn($unitDTO->allIn);
        $unit->setCreated(new \DateTime());
        $unit->setCreatedBy($user);

        $this->entityManager->persist($unit);
        $this->entityManager->flush();

        return $unit;
    }

    public function getEdit(Unit $unit): UnitDTO
    {
        $unitDTO = new UnitDTO();
        $unitDTO->name = $unit->getName();
        $unitDTO->allIn = $unit->isAllIn();

        return $unitDTO;
    }

    public function edit(Unit $unit, UnitDTO $unitDTO, User $user): Unit
    {
        $unit->setName($unitDTO->name);
        $unit->setAllIn($unitDTO->allIn);
        $unit->setUpdated(new \DateTime());
        $unit->setUpdatedBy($user);

        $this->entityManager->flush();

        return $unit;
    }

    public function delete(Unit $unit): void
    {
        $this->entityManager->remove($unit);
        $this->entityManager->flush();
    }
}
