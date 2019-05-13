<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\TimeTrackItemDTO;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

    public function __construct(
        TranslatorInterface $translator
    ) {
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
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.archived = false')
                        ->orderBy('p.lastUsed', 'DESC')
                    ;
                },
                'choice_label' => function (Project $project) {
                    $label = $project->getName();

                    if ($project->getCustomer() !== null) {
                        $label .= ', ' . $project->getCustomer()->getName();
                    }

                    return $label;
                },
                'label' => 'Project',
                'required' => true,
            ])
            ->add('person', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastUsed', 'DESC')
                        ;
                },
                'choice_label' => 'name',
                'label' => 'Person',
                'required' => true,
            ])
            ->add('moment', DateType::class, [
                'label' => 'Moment',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('date_format_form'),
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
