<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\TimeTrackItemDTO;
use App\Entity\TimeTrackItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TimeTrackItemManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(TimeTrackItemDTO $timeTrackItemDTO, User $user): TimeTrackItem
    {
        $timeTrackItem = new TimeTrackItem();
        $timeTrackItem->setProject($timeTrackItemDTO->project);
        $timeTrackItem->setMoment($timeTrackItemDTO->moment);
        $timeTrackItem->setDuration($timeTrackItemDTO->duration);
        $timeTrackItem->setDescription($timeTrackItemDTO->description);
        $timeTrackItem->setChargeable($timeTrackItemDTO->chargeable);
        $timeTrackItem->setCreated(new \DateTime());
        $timeTrackItem->setCreatedBy($user);

        $this->entityManager->persist($timeTrackItem);
        $this->entityManager->flush();

        return $timeTrackItem;
    }

    public function getEdit(TimeTrackItem $timeTrackItem): TimeTrackItemDTO
    {
        $timeTrackItemDTO = new TimeTrackItemDTO();
        $timeTrackItemDTO->project = $timeTrackItem->getProject();
        $timeTrackItemDTO->moment = $timeTrackItem->getMoment();
        $timeTrackItemDTO->duration = $timeTrackItem->getDuration();
        $timeTrackItemDTO->description = $timeTrackItem->getDescription();
        $timeTrackItemDTO->chargeable = $timeTrackItem->isChargeable();

        return $timeTrackItemDTO;
    }

    public function edit(
        TimeTrackItem $timeTrackItem,
        TimeTrackItemDTO $timeTrackItemDTO,
        User $user
    ): TimeTrackItem {
        $timeTrackItem->setProject($timeTrackItemDTO->project);
        $timeTrackItem->setMoment($timeTrackItemDTO->moment);
        $timeTrackItem->setDuration($timeTrackItemDTO->duration);
        $timeTrackItem->setDescription($timeTrackItemDTO->description);
        $timeTrackItem->setChargeable($timeTrackItemDTO->chargeable);
        $timeTrackItem->setUpdated(new \DateTime());
        $timeTrackItem->setUpdatedBy($user);

        $this->entityManager->flush();

        return $timeTrackItem;
    }

    public function delete(TimeTrackItem $timeTrackItem): void
    {
        $this->entityManager->remove($timeTrackItem);
        $this->entityManager->flush();
    }
}
