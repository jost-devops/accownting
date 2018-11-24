<?php declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Customer;
use App\Entity\Unit;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UnitNormalizer implements NormalizerInterface
{
    /**
     * @param Unit $object
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
            'name' => $object->getName(),
        ];
    }

    /**
     * @SuppressWarnings("unused")
     * @codeCoverageIgnore
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Unit;
    }
}
