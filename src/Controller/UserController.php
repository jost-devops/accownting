<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use App\Normalizer\UserNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        UserNormalizer $userNormalizer
    ): Response {
        /** @var User[] $users */
        $users = $entityManager->getRepository(User::class)->findAll();

        $response = [
            'data' => [],
        ];

        foreach ($users as $user) {
            $response['data'][] = $userNormalizer->normalize($user);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        UserManager $userManager
    ): Response {
        /** @var User $actingUser */
        $actingUser = $this->getUser();

        $userDTO = new UserDTO();

        $form = $this->createForm(UserType::class, $userDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->add($userDTO, $actingUser);

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/form.html.twig', [
            'title' => 'Add User',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        User $user,
        UserManager $userManager
    ): Response {
        /** @var User $actingUser */
        $actingUser = $this->getUser();

        $userDTO = $userManager->getEdit($user);

        $form = $this->createForm(UserType::class, $userDTO, ['password_required' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->edit($user, $userDTO, $actingUser);

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/form.html.twig', [
            'title' => 'Edit User',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        User $user,
        UserManager $userManager
    ): Response {
        $userManager->delete($user);

        return $this->redirectToRoute('app_user_index');
    }
}
