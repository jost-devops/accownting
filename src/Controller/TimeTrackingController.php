<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\TimeTrackingFilterDTO;
use App\DTO\TimeTrackItemDTO;
use App\Entity\TimeTrackItem;
use App\Entity\User;
use App\Form\TimeTrackFilterType;
use App\Form\TimeTrackItemType;
use App\Manager\TimeTrackItemManager;
use App\Repository\TimeTrackingItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/time-tracking")
 */
class TimeTrackingController extends Controller
{
    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $timeTrackingFilterDTO = new TimeTrackingFilterDTO();

        $form = $this->createForm(TimeTrackFilterType::class, $timeTrackingFilterDTO);
        $form->handleRequest($request);

        if ($timeTrackingFilterDTO->date === null) {
            $timeTrackingFilterDTO->date = new \DateTime();
        }

        /** @var TimeTrackingItemRepository $timeTrackItemRepository */
        $timeTrackItemRepository = $entityManager->getRepository(TimeTrackItem::class);

        /** @var TimeTrackItem[] $timeTrackItems */
        $timeTrackItems = $timeTrackItemRepository->findByDate($timeTrackingFilterDTO->date);

        return $this->render('time-tracking/index.html.twig', [
            'filterForm' => $form->createView(),
            'timeTrackItems' => $timeTrackItems,
        ]);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        TimeTrackItemManager $timeTrackItemManager,
        SessionInterface $session
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $timeTrackItemDTO = new TimeTrackItemDTO();

        $form = $this->createForm(TimeTrackItemType::class, $timeTrackItemDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTrackItemManager->add($timeTrackItemDTO, $user);

            $session->set('lastProject', $timeTrackItemDTO->project->getId());

            return $this->redirectToRoute('app_timetracking_index');
        }

        return $this->render('time-tracking/form.html.twig', [
            'title' => 'Add Time Track Item',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        TimeTrackItem $timeTrackItem,
        TimeTrackItemManager $timeTrackItemManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $timeTrackItemDTO = $timeTrackItemManager->getEdit($timeTrackItem);

        $form = $this->createForm(TimeTrackItemType::class, $timeTrackItemDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTrackItemManager->edit($timeTrackItem, $timeTrackItemDTO, $user);

            return $this->redirectToRoute('app_timetracking_index');
        }

        return $this->render('time-tracking/form.html.twig', [
            'title' => 'Edit Time Track Item',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        TimeTrackItem $timeTrackItem,
        TimeTrackItemManager $timeTrackItemManager
    ): Response {
        $timeTrackItemManager->delete($timeTrackItem);

        return $this->redirectToRoute('app_timetracking_index');
    }
}