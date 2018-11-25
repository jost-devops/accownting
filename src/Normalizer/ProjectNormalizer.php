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
        $progress = null;
        $hoursAvailable = null;
        $hoursSpent = null;
        $hoursSpentChargeable = null;

        if ($object->getBudget() && $object->getPricePerHour()) {
            $hoursAvailable = $object->getBudget() / $object->getPricePerHour();
        }

        $hoursSpent = 0;
        $hoursSpentChargeable = 0;

        foreach ($object->getTimeTrackItems() as $timeTrackItem) {
            $hoursSpent += $timeTrackItem->getDuration();

            if ($timeTrackItem->isChargeable()) {
                $hoursSpentChargeable += $timeTrackItem->getDuration();
            }
        }

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
            'hoursAvailable' => $hoursAvailable,
            'hoursSpent' => $hoursSpent,
            'hoursSpentChargeable' => $hoursSpentChargeable,
            'hoursUsage' => ($hoursAvailable !== null && $hoursSpentChargeable !== null)
                ? $hoursSpentChargeable / ($hoursAvailable / 100)
                : null,
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