<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\TimeTrackItemDTO;
use App\Entity\Company;
use App\Entity\Project;
use App\Entity\TimeTrackItem;
use App\Entity\User;
use App\Form\TimeTrackItemType;
use App\Helper\CurrentCompanyHelper;
use App\Manager\TimeTrackItemManager;
use App\Repository\TimeTrackingItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/time-tracking")
 */
class TimeTrackingController extends AbstractController
{
    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction(
        Request $request,
        EntityManagerInterface $entityManager,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

        /** @var TimeTrackingItemRepository $timeTrackItemRepository */
        $timeTrackItemRepository = $entityManager->getRepository(TimeTrackItem::class);

        $date = $request->query->get('date');

        if ($date === null) {
            $date = new \DateTime();
        } else {
            $date = new \DateTime($date);
        }

        $timelineBegin = (clone $date)->modify('first day of this month');
        $timelineEnd = (clone $date)->modify('last day of this month');

        $month = $timelineBegin->format('F Y');
        $days = [];

        while ($timelineBegin <= $timelineEnd) {
            $days[] = [
                'date' => $timelineBegin->format('Y-m-d'),
                'day' => $timelineBegin->format('d'),
                'duration' => round($timeTrackItemRepository->findDurationByDate($company, $timelineBegin), 1),
            ];

            $timelineBegin->add(new \DateInterval('P1D'));
        }

        /** @var TimeTrackItem[] $timeTrackItems */
        $timeTrackItems = $timeTrackItemRepository->findByDate($company, $date);

        $totalTime = 0;
        $totalTimeChargeable = 0;

        foreach ($timeTrackItems as $timeTrackItem) {
            $totalTime += $timeTrackItem->getDuration();

            if ($timeTrackItem->isChargeable()) {
                $totalTimeChargeable += $timeTrackItem->getDuration();
            }
        }

        return $this->render('time-tracking/index.html.twig', [
            'date' => $date,
            'timeTrackItems' => $timeTrackItems,
            'totalTime' => $totalTime,
            'totalTimeChargeable' => $totalTimeChargeable,
            'month' => $month,
            'days' => $days,
            'prevMonth' => (clone $date)->sub(new \DateInterval('P1M')),
            'nextMonth' => (clone $date)->add(new \DateInterval('P1M')),
        ]);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        TimeTrackItemManager $timeTrackItemManager,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

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

        $form = $this->createForm(TimeTrackItemType::class, $timeTrackItemDTO, ['company' => $company]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTrackItemManager->add($timeTrackItemDTO, $user);

            return $this->redirectToRoute('app_timetracking_index', [
                'date' => $timeTrackItemDTO->moment->format('Y-m-d'),
            ]);
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
        TimeTrackItemManager $timeTrackItemManager,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

        /** @var User $user */
        $user = $this->getUser();

        $timeTrackItemDTO = $timeTrackItemManager->getEdit($timeTrackItem);

        $form = $this->createForm(TimeTrackItemType::class, $timeTrackItemDTO, ['company' => $company]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTrackItemManager->edit($timeTrackItem, $timeTrackItemDTO, $user);

            return $this->redirectToRoute('app_timetracking_index', [
                'date' => $timeTrackItemDTO->moment->format('Y-m-d'),
            ]);
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
        $moment = $timeTrackItem->getMoment();

        $timeTrackItemManager->delete($timeTrackItem);

        return $this->redirectToRoute('app_timetracking_index', [
            'date' => $moment->format('Y-m-d'),
        ]);
    }

    /**
     * @Route("/by-project/{id}")
     */
    public function byProjectAction(
        EntityManagerInterface $entityManager,
        Project $project
    ): Response {
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
        Request $request
    ): Response {
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
