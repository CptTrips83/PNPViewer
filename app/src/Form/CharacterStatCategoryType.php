<?php

namespace App\Form;

use App\Entity\CharacterClass;
use App\Entity\CharacterStatCategory;
use App\Entity\RuleSet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterStatCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('description', TextareaType::class , [
                'label' => 'Beschreibung',
                'required' => false
            ])
            ->add('statsRequired', NumberType::class, [
                'label' => 'Anzahl erforderlicher Stats aus dieser Kategorie (-1 = alle Stats werden benötigt)',
                'data' => '1',
                'scale' => 0,
            ])
            ->add('ruleSet', EntityType::class, [
                'class' => RuleSet::class,
                'choice_label' => 'name',
                'label' => 'Regelwerk'
            ])
            ->add('classNeeded', EntityType::class, [
                'class' => CharacterClass::class,
                'choice_label' => 'name',
                'placeholder' => 'Keine Klasse',
                'empty_data' => null,
                'label' => 'Klasse',
                'required' => false
            ])
            ->add('Speichern', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterStatCategory::class,
        ]);
    }
}
