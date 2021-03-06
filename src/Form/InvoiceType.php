<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\InvoiceDTO;
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
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InvoiceType extends AbstractType
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
            ->add('invoiceNumber', IntegerType::class, [
                'label' => 'Invoice Number',
                'required' => true,
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'required' => true,
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Text',
                'required' => true,
            ])
            ->add('invoiceDate', DateType::class, [
                'label' => 'Invoice Date',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('date_format_form'),
            ])
            ->add('timeOfSupply', DateType::class, [
                'label' => 'Time of Supply',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('date_format_form'),
            ])
            ->add('timeOfSupplyEnd', DateType::class, [
                'label' => 'End Time of Supply',
                'required' => false,
                'widget' => 'single_text',
                'format' => $this->translator->trans('date_format_form'),
            ])
            ->add('creditPeriod', IntegerType::class, [
                'label' => 'Credit Period',
                'required' => false,
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Payment Method',
                'required' => true,
                'choices' => [
                    'Bankwire' => 'bankwire',
                    'PayPal' => 'paypal',
                ],
            ])
            ->add('items', CollectionType::class, [
                'entry_type' => InvoiceItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvoiceDTO::class,
            'company' => null,
        ]);
    }
}
