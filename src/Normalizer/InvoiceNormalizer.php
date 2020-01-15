<?php declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Invoice;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class InvoiceNormalizer implements NormalizerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Invoice $object
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|string
     *
     * @SuppressWarnings("unused")
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'invoiceNumber' => $object->getInvoiceNumber(),
            'invoiceDate' => $object->getInvoiceDate()->format('Y-m-d'),
            'company' => ($object->getCompany() !== null) ? $object->getCompany()->getName() : '',
            'customer' => ($object->getCustomer() !== null) ? $object->getCustomer()->getName() : '',
            'subject' => $object->getSubject(),
            'totalNetPrice' => number_format($object->getTotalNetPrice(), 2),
            'totalGrossPrice' => number_format($object->getTotalGrossPrice(), 2),
            'paid' => ($object->getPaid() !== null) ?
                $object->getPaid()->format('Y-m-d') :
                null,
        ];
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     *
     * @SuppressWarnings("unused")
     * @codeCoverageIgnore
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Invoice;
    }
}
