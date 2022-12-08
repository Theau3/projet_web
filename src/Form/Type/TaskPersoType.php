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
            'label' => 'Informations sur la tâche',
        ]); //On ajoute la tâche en elle même
        $builder
            ->add('dateDebut', DateType::class)
            ->add('dueDate', DateType::class)
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