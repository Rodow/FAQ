<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la question :',
                'attr' => [
                    'placeholder' => 'Choisissez un titre qui permettra d\'identifier rapidement le type de question'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Question :',
                'attr' => [
                    'placeholder' => 'n\'hésitez pas à ajouter du détail ;) '
                ]
            ])
            ->add('tags', EntityType::class, [
                'label' => "Merci de choisir un ou plusieurs tag :",
                'required' => true,
                // looks for choices from this entity
                'class' => Tag::class,
                // uses the  property as the visible option string
                'choice_label' => 'name',
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
