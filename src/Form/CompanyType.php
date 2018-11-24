<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\CompanyDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    /**
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
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
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'required' => false,
            ])
            ->add('fax', TextType::class, [
                'label' => 'Fax',
                'required' => false,
            ])
            ->add('website', UrlType::class, [
                'label' => 'Website',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
            ])
            ->add('vatNumber', TextType::class, [
                'label' => 'VAT Number',
                'required' => false,
            ])
            ->add('taxNumber', TextType::class, [
                'label' => 'Tax Number',
                'required' => false,
            ])
            ->add('districtCourt', TextType::class, [
                'label' => 'District Court',
                'required' => false,
            ])
            ->add('companyRegisterId', TextType::class, [
                'label' => 'Company Register ID',
                'required' => false,
            ])
            ->add('managingDirector', TextType::class, [
                'label' => 'Managing Director',
                'required' => false,
            ])
            ->add('bankName', TextType::class, [
                'label' => 'Bank name',
                'required' => false,
            ])
            ->add('bankIban', TextType::class, [
                'label' => 'IBAN',
                'required' => false,
            ])
            ->add('bankBic', TextType::class, [
                'label' => 'BIC',
                'required' => false,
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
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
            'data_class' => CompanyDTO::class,
        ]);
    }
}
