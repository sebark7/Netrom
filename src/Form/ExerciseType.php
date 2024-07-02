<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>"Name",
                'required' => true
            ])
            ->add('type', TextType::class, [
                'label' => "Type",
                'required' => true
            ])
            ->add('muscleGroup', EntityType::class, [
                'class' => MuscleGroup::class,
                'choice_label' => 'tipul',
                'label' => "Muscle Group",
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'
                ],
                'expanded' => true,

            ])
            ->add('button', SubmitType::class,[
                'label' => "Submit",
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
