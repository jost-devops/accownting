<?php declare(strict_types=1);

namespace App\Form;

use App\DTO\ProjectDTO;
use App\Entity\Company;
use App\Entity\Customer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProjectType extends AbstractType
{
    /**
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Company $company */
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
            ->add('projectNumber', IntegerType::class, [
                'label' => 'Project Number',
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
            ])
            ->add('budget', MoneyType::class, [
                'label' => 'Budget',
                'required' => false,
            ])
            ->add('pricePerHour', MoneyType::class, [
                'label' => 'Price per Hour',
                'required' => false,
            ])
            ->add('budgetBilled', MoneyType::class, [
                'label' => 'Budget billed',
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
            'data_class' => ProjectDTO::class,
            'company' => null,
        ]);
    }
}
