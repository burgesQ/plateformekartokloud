<?php
/**
 * Created by PhpStorm.
 * User: mikaz3
 * Date: 2/4/18
 * Time: 9:30 AM
 */

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\Company;
use App\Entity\User;

class JoinCompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('company', EntityType::class, [
                'class'        => Company::class,
                'choices'      => $options['companies'],
                'choice_label' => 'name',
                'mapped'       => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(
            ['data_class'        => null,
             'validation_groups' => false])
                 ->setRequired(['companies']);
    }
}