<?php declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Document;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DocumentNormalizer implements NormalizerInterface
{
    /**
     * @param Document $object
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
            'company' => ($object->getCompany() !== null) ? $object->getCompany()->getName() : null,
            'date' => $object->getDate()->format('Y-m-d'),
            'title' => $object->getTitle(),
            'filename' => $object->getFileName(),
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
        return $data instanceof Document;
    }
}
