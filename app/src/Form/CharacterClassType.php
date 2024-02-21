<?php

namespace App\Form;

use App\Entity\CharacterClass;
use App\Entity\RuleSet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Anzeige'
            ])
            ->add('longDescription', TextareaType::class, [
                'required' => false,
                'label' => 'Lange Beschreibung'
            ])
            ->add('lowestLevel', NumberType::class, [
                'label' => 'Niedrigster Level',
                'scale' => 0
            ])
            ->add('highestLevel', NumberType::class, [
                'label' => 'Höchster Level',
                'scale' => 0
            ])
            ->add('ruleSet', EntityType::class, [
                'class' => RuleSet::class,
                'choice_label' => 'name',
                'label' => 'Regelwerk'
            ])
            ->add('Speichern', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterClass::class,
        ]);
    }
}
