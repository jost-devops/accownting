<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\ProjectDTO;
use App\DTO\TimeTrackingExportDTO;
use App\DTO\TimeTrackingFilterDTO;
use App\DTO\TimeTrackItemDTO;
use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TimeTrackingExportType extends AbstractType
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
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin', DateType::class, [
                'label' => 'Begin',
                'required' => true,
                'format' => $this->translator->trans('date_format_form'),
                'data' => (new \DateTime())->sub(new \DateInterval('P1M')),
            ])
            ->add('end', DateType::class, [
                'label' => 'End',
                'required' => true,
                'format' => $this->translator->trans('date_format_form'),
                'data' => new \DateTime(),
            ])
            ->add('includeNonChargeable', CheckboxType::class, [
                'label' => 'Include non chargeable?',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Export to PDF',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeTrackingExportDTO::class,
        ]);
    }
}
