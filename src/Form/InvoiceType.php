<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\InvoiceDTO;
use App\Entity\Company;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    /**
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'label' => 'Company',
                'required' => true,
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
                'label' => 'Customer',
                'required' => true,
            ])
            ->add('invoiceNumber', TextType::class, [
                'label' => 'Invoice Number',
                'required' => true,
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'required' => true,
            ])
            ->add('invoiceDate', DateType::class, [
                'label' => 'Invoice Date',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('timeOfSupply', DateType::class, [
                'label' => 'Time of Supply',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('timeOfSupplyEnd', DateType::class, [
                'label' => 'End Time of Supply',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('creditPeriod', IntegerType::class, [
                'label' => 'Credit Period',
                'required' => false,
            ])
            ->add('lineItems', CollectionType::class, [
                'entry_type' => InvoiceLineItemType::class,
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
        ]);
    }
}