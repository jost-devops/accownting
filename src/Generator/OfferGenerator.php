<?php declare(strict_types=1);

namespace App\Generator;

use App\Entity\Invoice;
use App\Entity\Offer;
use Twig\Environment;

class OfferGenerator
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generate(Offer $offer): string
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => 'freesans',
            'margin_left' => 23,
            'margin_right' => 23,
            'margin_top' => 95,
            'margin_bottom' => 40,
            'margin_header' => 20,
            'margin_footer' => 3,
            'tempDir' => '/tmp',
        ]);

        $html = $this->twig->render('pdf/offer.' . $offer->getCountry() . '.html.twig', [
            'offer' => $offer,
        ]);

        $mpdf->WriteHTML($html);

        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }
}
