<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\UnitDTO;
use App\Entity\Unit;
use App\Entity\User;
use App\Form\UnitType;
use App\Manager\UnitManager;
use App\Normalizer\UnitNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/unit")
 */
class UnitController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('unit/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        UnitNormalizer $unitNormalizer
    ): Response {
        /** @var Unit[] $units */
        $units = $entityManager->getRepository(Unit::class)->findAll();

        $response = [
            'data' => [],
        ];

        foreach ($units as $unit) {
            $response['data'][] = $unitNormalizer->normalize($unit);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        UnitManager $unitManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $unitDTO = new UnitDTO();

        $form = $this->createForm(UnitType::class, $unitDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unitManager->add($unitDTO, $user);

            return $this->redirectToRoute('app_unit_index');
        }

        return $this->render('unit/form.html.twig', [
            'title' => 'Add Unit',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Unit $unit,
        UnitManager $unitManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $unitDTO = $unitManager->getEdit($unit);

        $form = $this->createForm(UnitType::class, $unitDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unitManager->edit($unit, $unitDTO, $user);

            return $this->redirectToRoute('app_unit_index');
        }

        return $this->render('unit/form.html.twig', [
            'title' => 'Edit Unit',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Unit $unit,
        UnitManager $unitManager
    ): Response {
        $unitManager->delete($unit);

        return $this->redirectToRoute('app_unit_index');
    }
}
