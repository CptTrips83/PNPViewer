<?php

namespace App\Form;

use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterStatType extends AbstractType
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
            ->add('lowestValue', NumberType::class, [
                'label' => 'Niedrigster Wert',
                'scale' => 0
            ])
            ->add('highestValue', NumberType::class, [
                'label' => 'Höchster Wert',
                'scale' => 0
            ])
            ->add('category', EntityType::class, [
                'class' => CharacterStatCategory::class,
                'choice_label' => 'name',
                'label' => 'Kategorie'
            ])
            ->add('Speichern', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterStat::class,
        ]);
    }
}
