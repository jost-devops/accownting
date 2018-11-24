<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\CustomerDTO;
use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
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
            ->add('customerNumber', TextType::class, [
                'label' => 'Customer Number',
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
            ])
            ->add('additionalName', TextType::class, [
                'label' => 'Additional Name',
                'required' => false,
            ])
            ->add('street', TextType::class, [
                'label' => 'Street',
                'required' => false,
            ])
            ->add('zip', TextType::class, [
                'label' => 'Zip',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => false,
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'required' => false,
            ])
            ->add('salutation', TextType::class, [
                'label' => 'Salutation',
                'required' => false,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
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
            'data_class' => CustomerDTO::class,
        ]);
    }
}
