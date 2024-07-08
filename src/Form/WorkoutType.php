<?php

namespace App\Form;

use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Entity\Workout;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class WorkoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => "Name",
                'required' => true,
            ])
            ->add('exerciseLogs', CollectionType::class,[
                'entry_type' => ExerciseLogType::class,
                'entry_options' => ['label' =>false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' =>true,
            ])
            ->add('button', SubmitType::class,[
                'label'=>'Add'
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workout::class,
        ]);
    }
}
