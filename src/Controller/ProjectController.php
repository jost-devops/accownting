<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Manager\ProjectManager;
use App\Normalizer\ProjectNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('project/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        ProjectNormalizer $projectNormalizer
    ): Response {
        /** @var Project[] $projects */
        $projects = $entityManager->getRepository(Project::class)->findAll();

        $response = [
            'data' => [],
        ];

        foreach ($projects as $project) {
            $response['data'][] = $projectNormalizer->normalize($project);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        ProjectManager $projectManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $projectDTO = new ProjectDTO();

        $form = $this->createForm(ProjectType::class, $projectDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectManager->add($projectDTO, $user);

            return $this->redirectToRoute('app_project_index');
        }

        return $this->render('project/form.html.twig', [
            'title' => 'Add Project',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Project $project,
        ProjectManager $projectManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $projectDTO = $projectManager->getEdit($project);

        $form = $this->createForm(ProjectType::class, $projectDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectManager->edit($project, $projectDTO, $user);

            return $this->redirectToRoute('app_project_index');
        }

        return $this->render('project/form.html.twig', [
            'title' => 'Edit Project',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Project $project,
        ProjectManager $projectManager
    ): Response {
        $projectManager->delete($project);

        return $this->redirectToRoute('app_project_index');
    }
}
