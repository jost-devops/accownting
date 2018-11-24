<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\InvoiceLineItemDTO;
use App\Entity\Unit;
use App\Entity\VatRate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceLineItemType extends AbstractType
{
    /**
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Amount',
                'required' => true,
            ])
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'choice_label' => 'name',
                'label' => 'Unit',
                'required' => true,
            ])
            ->add('priceSingle', MoneyType::class, [
                'label' => 'Single Price',
                'required' => true,
            ])
            ->add('vatRate', EntityType::class, [
                'class' => VatRate::class,
                'choice_label' => 'name',
                'label' => 'VAT',
                'required' => true,
                'choice_attr' => function (VatRate $choiceValue, $key, $value) {
                    return ['data-rate' => $choiceValue->getRate()];
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvoiceLineItemDTO::class,
        ]);
    }
}
