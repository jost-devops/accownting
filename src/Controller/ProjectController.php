<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Manager\ProjectManager;
use App\Normalizer\ProjectNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('project/index.html.twig');
    }

    /**
     * @Route("/archive", methods={"GET"})
     */
    public function archiveAction(): Response
    {
        return $this->render('project/archive.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        ProjectNormalizer $projectNormalizer,
        Request $request
    ): Response {
        /** @var Project[] $projects */
        $projects = $entityManager
            ->getRepository(Project::class)
            ->findBy(['archived' => ($request->query->get('archive') !== null)]);

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
        ProjectManager $projectManager,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $projectDTO = new ProjectDTO();

        $form = $this->createForm(ProjectType::class, $projectDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $projectManager->add($projectDTO, $user);
            $company = $project->getCompany();

            if ($company !== null) {
                if ($company->getNextProjectNumber() === $project->getProjectNumber()) {
                    $company->setNextProjectNumber($project->getProjectNumber() + 1);

                    $entityManager->flush();
                }
            }

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

    /**
     * @Route("/{id}/toggle-archive")
     */
    public function toggleArchiveAction(
        Project $project,
        EntityManagerInterface $entityManager
    ): Response {
        $project->setArchived(!$project->isArchived());

        $entityManager->flush();

        return $this->redirectToRoute(($project->isArchived()) ? 'app_project_archive' : 'app_project_index');
    }
}
