<?php
namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class NewProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pp', ChoiceType::class, [
            'choices' => [
                'profile_pictures/pp1.png' => 'pp1.png',
                'profile_pictures/pp2.png' => 'pp2.png',
                'profile_pictures/pp3.png' => 'pp3.png',
                'profile_pictures/pp4.png' => 'pp4.png',
                'profile_pictures/pp5.png' => 'pp5.png',
            ],
            'expanded' => true,
            'choice_label' => function($profilePicturePath) {
                return '<img src="/profile_pictures/'.$profilePicturePath.'" alt="Profile picture" />';
            },
            'label_html'=>true,
            'label' => 'Choisissez votre nouvelle photo de profil',
        ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}