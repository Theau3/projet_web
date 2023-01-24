<?php
// src/Form/TaskPersoType.php
namespace App\Form\Type;

use App\Entity\TaskPerso;
use App\Form\Type\TaskType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskPersoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('Task', TaskType::class, [
            'label' => false,
        ]); //On ajoute la tâche en elle même
        $builder
            ->add('dateDebut', DateType::class,
                [
                    'label' => 'Date de début',
                    'html5' => false,
                    'attr' => ['class' => 'js-datepicker'],
                ])
            ->add('dueDate', DateType::class, 
            [
                'label' => 'Date de fin',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('Enregistrer', SubmitType::class)
            ;

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskPerso::class,
        ]);
    }
}