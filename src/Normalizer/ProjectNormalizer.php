<?php declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Project;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProjectNormalizer implements NormalizerInterface
{
    /**
     * @param Project $object
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
            'company' => ($object->getCompany() !== null) ? $object->getCompany()->getName() : '',
            'customer' => ($object->getCustomer() !== null) ? $object->getCustomer()->getName() : '',
            'budget' => ($object->getBudget() !== null)
                ? number_format($object->getBudget(), 2)
                : '',
            'pricePerHour' => ($object->getPricePerHour() !== null)
                ? number_format($object->getPricePerHour(), 2)
                : '',
        ];
    }

    /**
     * @SuppressWarnings("unused")
     * @codeCoverageIgnore
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Project;
    }
}
