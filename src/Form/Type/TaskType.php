<?php

namespace App\Form\Type;

//On sépare l'initialisation des types des éléments du controller, pour plus de clarté

use App\Entity\Task;
use App\Form\CategoryType;
use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;

class TaskType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre', TextType::class)
            ->add('Description', TextType::class)
            ->add('NombreFois', IntegerType::class, [
                'label' => 'Nombre de fois',
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le champ ne peut pas être inférieur à zéro.',
                    ]),
                ],
                'required' => false,
            ])
            ->add('ChoixTemps', ChoiceType::class, [
                'label' => 'Tous les : ',
                'choices' => [
                    'Jour' => 1,
                    'Semaine' => 7,
                    'Mois' => 30
                ],
                'choice_label' => function ($value, $key, $index) {
                    // utiliser la clé comme étiquette pour chaque choix
                    return $key;
                },
                'choice_value' => function ($value) {
                    // utiliser la valeur comme valeur pour chaque choix
                    return $value;
                },
                'multiple' => false,
            ])
        ;
        $categories = $this->categoryRepository->findAll();
        $builder->add('category', ChoiceType::class, [
            'label' => 'Sélectionnez la catégorie désirée',
            'choices' => $categories,
            'choice_label' => 'nom',
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    { //on spécifie explicitement la data class pour éviter des problèmes
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

}