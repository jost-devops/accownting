<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\OfferDTO;
use App\Entity\Company;
use App\Entity\Customer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OfferType extends AbstractType
{
    /**
     * @var array
     */
    private $countries;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(array $countries, TranslatorInterface $translator)
    {
        $this->countries = $countries;
        $this->translator = $translator;
    }

    /**
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $company = $options['company'];

        $builder
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'query_builder' => function (EntityRepository $er) use ($company) {
                    return $er->createQueryBuilder('c')
                        ->where('c.company = :company')
                        ->setParameter('company', $company)
                        ->orderBy('c.name', 'ASC')
                    ;
                },
                'choice_label' => 'name',
                'label' => 'Customer',
                'required' => true,
            ])
            ->add('country', ChoiceType::class, [
                'choices' => array_flip($this->countries),
                'label' => 'Country',
                'required' => true,
            ])
            ->add('offerNumber', IntegerType::class, [
                'label' => 'Offer Number',
                'required' => true,
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'required' => true,
            ])
            ->add('offerDate', DateType::class, [
                'label' => 'Offer Date',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('date_format_form'),
            ])
            ->add('items', CollectionType::class, [
                'entry_type' => OfferItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('additionalText', TextareaType::class, [
                'label' => 'Additional Text',
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
            'data_class' => OfferDTO::class,
            'company' => null,
        ]);
    }
}
