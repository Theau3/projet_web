<?php

namespace App\Form\Type;

//On sépare l'initialisation des types des éléments du controller, pour plus de clarté

use App\Entity\Task;
use App\Form\CategoryType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre', TextType::class)
            ->add('Description', TextType::class)
        ;
        $builder->add('category', CategoryType::class, [
            'label' => 'Entrez les catégories désirées',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    { //on spécifie explicitement la data class pour éviter des problèmes
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

}