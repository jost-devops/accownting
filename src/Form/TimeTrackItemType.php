<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\ProjectDTO;
use App\DTO\TimeTrackItemDTO;
use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
class TimeTrackItemType extends AbstractType
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
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => function (Project $project) {
                    $label = $project->getName();

                    if ($project->getCompany() !== null) {
                        $label .= ', ' . $project->getCompany()->getName();
                    }

                    return $label;
                },
                'label' => 'Project',
                'required' => true,
            ])
            ->add('moment', DateTimeType::class, [
                'label' => 'Moment',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('datetime_format_form'),
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Duration',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('chargeable', CheckboxType::class, [
                'label' => 'Chargeable',
                'required' => false,
                'data' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeTrackItemDTO::class,
        ]);
    }
}
