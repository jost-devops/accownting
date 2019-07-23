<?php declare(strict_types=1);

namespace App\Generator;

use App\DTO\TimeTrackingExportDTO;
use App\Entity\Project;
use App\Entity\TimeTrackItem;
use App\Repository\TimeTrackingItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class TimeTrackingTableGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    public function generate(Project $project, TimeTrackingExportDTO $timeTrackingExportDTO): string
    {
        /** @var TimeTrackingItemRepository $timeTrackingItemRepository */
        $timeTrackingItemRepository = $this->entityManager
            ->getRepository(TimeTrackItem::class);

        /** @var TimeTrackItem[] $items */
        $items = $timeTrackingItemRepository->findByProjectForExport($project, $timeTrackingExportDTO);

        $totalDuration = 0;
        $chargeableDuration = 0;
        $notChargeableDuration = 0;

        foreach ($items as $item) {
            $totalDuration += $item->getDuration();

            if ($item->isChargeable()) {
                $chargeableDuration += $item->getDuration();
            } else {
                $notChargeableDuration += $item->getDuration();
            }
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => 'freesans',
            'margin_left' => 23,
            'margin_right' => 23,
            'margin_top' => 50,
            'margin_bottom' => 40,
            'margin_header' => 20,
            'margin_footer' => 3,
            'tempDir' => '/tmp',
        ]);

        $html = $this->twig->render('pdf/time-tracking.html.twig', [
            'timeTrackingExportDTO' => $timeTrackingExportDTO,
            'project' => $project,
            'items' => $items,
            'totalDuration' => $totalDuration,
            'chargeableDuration' => $chargeableDuration,
            'notChargeableDuration' => $notChargeableDuration,
        ]);

        $mpdf->WriteHTML($html);

        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }
}
