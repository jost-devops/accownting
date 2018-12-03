<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\OfferDTO;
use App\Entity\Offer;
use App\Entity\OfferItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OfferManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OfferItemManager
     */
    private $offerItemManager;

    public function __construct(EntityManagerInterface $entityManager, OfferItemManager $offerItemManager)
    {
        $this->entityManager = $entityManager;
        $this->offerItemManager = $offerItemManager;
    }

    public function add(OfferDTO $offerDTO, User $user): Offer
    {
        $offer = new Offer();
        $offer->setCompany($offerDTO->company);
        $offer->setCustomer($offerDTO->customer);
        $offer->setCountry($offerDTO->country);
        $offer->setOfferNumber($offerDTO->offerNumber);
        $offer->setSubject($offerDTO->subject);
        $offer->setOfferDate($offerDTO->offerDate);
        $offer->setCreated(new \DateTime());
        $offer->setCreatedBy($user);

        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        foreach ($offerDTO->items as $itemDTO) {
            $this->offerItemManager->add($itemDTO, $offer, $user);
        }

        return $offer;
    }

    public function getEdit(Offer $offer): OfferDTO
    {
        $offerDTO = new OfferDTO();
        $offerDTO->company = $offer->getCompany();
        $offerDTO->customer = $offer->getCustomer();
        $offerDTO->country = $offer->getCountry();
        $offerDTO->offerNumber = $offer->getOfferNumber();
        $offerDTO->subject = $offer->getSubject();
        $offerDTO->offerDate = $offer->getOfferDate();

        foreach ($offer->getItems() as $item) {
            $offerDTO->items[] = $this->offerItemManager->getEdit($item);
        }

        return $offerDTO;
    }

    public function edit(
        Offer $offer,
        OfferDTO $offerDTO,
        User $user
    ): Offer {
        $offer->setCompany($offerDTO->company);
        $offer->setCustomer($offerDTO->customer);
        $offer->setCountry($offerDTO->country);
        $offer->setOfferNumber($offerDTO->offerNumber);
        $offer->setSubject($offerDTO->subject);
        $offer->setOfferDate($offerDTO->offerDate);
        $offer->setUpdated(new \DateTime());
        $offer->setUpdatedBy($user);

        foreach ($offerDTO->items as $itemDTO) {
            if ($itemDTO->id !== null) {
                /** @var OfferItem|null $lineItem */
                $offerItem = $this->entityManager
                    ->getRepository(OfferItem::class)
                    ->findOneBy(['id' => $itemDTO->id]);

                if ($offerItem !== null) {
                    $this->offerItemManager->edit($offerItem, $itemDTO, $user);
                }
            } else {
                $this->offerItemManager->add($itemDTO, $offer, $user);
            }
        }

        $itemsToDelete = $offer->getItems();

        foreach ($itemsToDelete as $key => $item) {
            foreach ($offerDTO->items as $itemDTO) {
                if ($itemDTO->id === $item->getId()) {
                    unset($itemsToDelete[$key]);
                }
            }
        }

        foreach ($itemsToDelete as $item) {
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();

        return $offer;
    }

    public function delete(Offer $offer): void
    {
        $this->entityManager->remove($offer);
        $this->entityManager->flush();
    }
}
