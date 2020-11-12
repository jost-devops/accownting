<?php declare(strict_types=1);

namespace App\Generator;

use App\Entity\Invoice;
use Twig\Environment;

class InvoiceGenerator
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generate(Invoice $invoice): string
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => 'freesans',
            'margin_left' => 23,
            'margin_right' => 23,
            'margin_top' => 95,
            'margin_bottom' => 50,
            'margin_header' => 20,
            'margin_footer' => 9,
            'tempDir' => '/tmp',
        ]);

        $html = $this->twig->render('pdf/invoice.' . $invoice->getCountry() . '.html.twig', [
            'invoice' => $invoice,
        ]);

        $mpdf->WriteHTML($html);

        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }

    public function generateReminder(Invoice $invoice, int $level): string
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => 'freesans',
            'margin_left' => 23,
            'margin_right' => 23,
            'margin_top' => 105,
            'margin_bottom' => 50,
            'margin_header' => 20,
            'margin_footer' => 9,
            'tempDir' => '/tmp',
        ]);

        $html = $this->twig->render('pdf/invoice-reminder.' . $invoice->getCountry() . '.html.twig', [
            'invoice' => $invoice,
            'level' => $level,
        ]);

        $mpdf->WriteHTML($html);

        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }
}
