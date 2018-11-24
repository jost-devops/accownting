<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\UnitDTO;
use App\DTO\VatRateDTO;
use App\Entity\Unit;
use App\Entity\User;
use App\Entity\VatRate;
use Doctrine\ORM\EntityManagerInterface;

class VatRateManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(VatRateDTO $vatRateDTO, User $user): VatRate
    {
        $vatRate = new VatRate();
        $vatRate->setName($vatRateDTO->name);
        $vatRate->setRate($vatRateDTO->rate);
        $vatRate->setCreated(new \DateTime());
        $vatRate->setCreatedBy($user);

        $this->entityManager->persist($vatRate);
        $this->entityManager->flush();

        return $vatRate;
    }

    public function getEdit(VatRate $vatRate): VatRateDTO
    {
        $vatRateDTO = new VatRateDTO();
        $vatRateDTO->name = $vatRate->getName();
        $vatRateDTO->rate = $vatRate->getRate();

        return $vatRateDTO;
    }

    public function edit(VatRate $vatRate, VatRateDTO $vatRateDTO, User $user): VatRate
    {
        $vatRate->setName($vatRateDTO->name);
        $vatRate->setRate($vatRateDTO->rate);
        $vatRate->setUpdated(new \DateTime());
        $vatRate->setUpdatedBy($user);

        $this->entityManager->flush();

        return $vatRate;
    }

    public function delete(VatRate $vatRate): void
    {
        $this->entityManager->remove($vatRate);
        $this->entityManager->flush();
    }
}
