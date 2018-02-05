<?php
/**
 * Created by PhpStorm.
 * User: mikaz3
 * Date: 2/4/18
 * Time: 9:30 AM
 */

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use App\Entity\Company;

class JoinCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('company', EntityType::class, [
            'class' => Company::class,
            'choices' => $options['companies'],
            'choice_label' => 'name'
        ]);
        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => false
        ]);
        $resolver->setRequired(['companies']);
    }
}