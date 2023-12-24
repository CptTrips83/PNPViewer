<?php

namespace App\Form;

use App\Entity\PNPGroup;
use App\Entity\PNPUser;
use App\Entity\RuleSet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PNPGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextareaType::class,[
                'label' => 'Name'
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Beschreibung',
                'required' => false
            ])
            ->add('ruleSet', EntityType::class, [
                'class' => RuleSet::class,
                'choice_label' => 'name',
                'label' => 'Regelwerk'
            ])
            ->add('fortfahren', SubmitType::class, [
                'label' => 'Speichern'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PNPGroup::class,
            'allow_extra_fields' => true
        ]);
    }
}
