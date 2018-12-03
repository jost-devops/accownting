<?php declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Offer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class OfferNormalizer implements NormalizerInterface
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
     * @param Offer $object
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
            'offerNumber' => $object->getOfferNumber(),
            'offerDate' => ($object->getOfferDate() !== null) ?
                $object->getOfferDate()->format($this->translator->trans('date_format')) :
                null,
            'company' => ($object->getCompany() !== null) ? $object->getCompany()->getName() : '',
            'customer' => ($object->getCustomer() !== null) ? $object->getCustomer()->getName() : '',
            'subject' => $object->getSubject(),
            'totalNetPrice' => number_format($object->getTotalNetPrice(), 2),
            'totalGrossPrice' => number_format($object->getTotalGrossPrice(), 2),
        ];
    }

    /**
     * @SuppressWarnings("unused")
     * @codeCoverageIgnore
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Offer;
    }
}
