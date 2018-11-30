<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\TimeTrackingFilterDTO;
use App\DTO\TimeTrackItemDTO;
use App\Entity\Project;
use App\Entity\TimeTrackItem;
use App\Entity\User;
use App\Form\TimeTrackFilterType;
use App\Form\TimeTrackItemType;
use App\Manager\TimeTrackItemManager;
use App\Repository\TimeTrackingItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $totalTime = 0;
        $totalTimeChargeable = 0;

        foreach ($timeTrackItems as $timeTrackItem) {
            $totalTime += $timeTrackItem->getDuration();

            if ($timeTrackItem->isChargeable()) {
                $totalTimeChargeable += $timeTrackItem->getDuration();
            }
        }

        return $this->render('time-tracking/index.html.twig', [
            'filterForm' => $form->createView(),
            'timeTrackItems' => $timeTrackItems,
            'totalTime' => $totalTime,
            'totalTimeChargeable' => $totalTimeChargeable,
        ]);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        TimeTrackItemManager $timeTrackItemManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $timeTrackItemDTO = new TimeTrackItemDTO();

        $moment = $request->query->get('moment');

        if ($moment !== null) {
            $moment = \DateTime::createFromFormat('Y-m-d', $moment);

            if ($moment !== false) {
                $timeTrackItemDTO->moment = $moment;
            }
        }

        $form = $this->createForm(TimeTrackItemType::class, $timeTrackItemDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTrackItemManager->add($timeTrackItemDTO, $user);

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

    /**
     * @Route("/by-project/{id}")
     */
    public function byProjectAction(
        EntityManagerInterface $entityManager,
        Project $project
    ): Response
    {
        /** @var TimeTrackingItemRepository $timeTrackItemRepository */
        $timeTrackItemRepository = $entityManager->getRepository(TimeTrackItem::class);

        /** @var TimeTrackItem[] $items */
        $items = $timeTrackItemRepository->findOpenByProject($project);

        $totalTime = 0;
        $totalTimeChargeable = 0;

        foreach ($items as $item) {
            $totalTime += $item->getDuration();

            if ($item->isChargeable()) {
                $totalTimeChargeable += $item->getDuration();
            }
        }

        return $this->render('time-tracking/by-project.html.twig', [
            'project' => $project,
            'items' => $items,
            'totalTime' => $totalTime,
            'totalTimeChargeable' => $totalTimeChargeable,
        ]);
    }

    /**
     * @Route("/clear", methods={"POST"})
     */
    public function clearAction(
        EntityManagerInterface $entityManager,
        Request $request):
    Response {
        /** @var TimeTrackingItemRepository $timeTrackItemRepository */
        $timeTrackItemRepository = $entityManager->getRepository(TimeTrackItem::class);

        /** @var TimeTrackItem[] $items */
        $items = $timeTrackItemRepository->findMultipleByIds($request->get('items'));

        foreach ($items as $item) {
            $item->setCleared(true);
        }

        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
