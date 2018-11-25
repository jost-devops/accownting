<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProjectManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(ProjectDTO $projectDTO, User $user): Project
    {
        $project = new Project();
        $project->setCompany($projectDTO->company);
        $project->setCustomer($projectDTO->customer);
        $project->setName($projectDTO->name);
        $project->setBudget($projectDTO->budget);
        $project->setPricePerHour($projectDTO->pricePerHour);
        $project->setCreated(new \DateTime());
        $project->setCreatedBy($user);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function getEdit(Project $project): ProjectDTO
    {
        $projectDTO = new ProjectDTO();
        $projectDTO->company = $project->getCompany();
        $projectDTO->customer = $project->getCustomer();
        $projectDTO->name = $project->getName();
        $projectDTO->budget = $project->getBudget();
        $projectDTO->pricePerHour = $project->getPricePerHour();

        return $projectDTO;
    }

    public function edit(
        Project $project,
        ProjectDTO $projectDTO,
        User $user
    ): Project {
        $project->setCompany($projectDTO->company);
        $project->setCustomer($projectDTO->customer);
        $project->setName($projectDTO->name);
        $project->setBudget($projectDTO->budget);
        $project->setPricePerHour($projectDTO->pricePerHour);
        $project->setUpdated(new \DateTime());
        $project->setUpdatedBy($user);

        $this->entityManager->flush();

        return $project;
    }

    public function delete(Project $project): void
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
}
