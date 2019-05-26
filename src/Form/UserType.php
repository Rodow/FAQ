<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $listener = function (FormEvent $event) {

            $userData = $event->getData();
            $userForm = $event->getForm();

            if($userData->getId()){
                
                //si mon user est créé j'ai un id mon champs n'est pas nécessaire
                return;

            } else {

                //sinon mon user est nouveau , mon champs est obligatoire
                $userForm->add('password', RepeatedType::class, array(
                    'constraints' => [new NotBlank()],
                    'first_options' => [
                        'label' => 'Mot de Passe',
                        'attr' => [
                            'placeholder' => 'Choisissez un mot de passe'
                        ]],
                    'second_options' => [
                        'label' => 'Vérification du mot de passe',
                        'attr' => [
                            'placeholder' => 'Répétez le mot de passde '
                        ]],
                ));
            }
        };

        $listener2 = function (FormEvent $event) {

            $userData = $event->getData();
            $userForm = $event->getForm();

            if($userData->getId()){
                if($userData->getRole()->getName() == 'admin') {
                    $userForm->add('role');
                } else {
                    return;
                }
            } else {
                return;
            }
        };

        $builder
            ->add('username')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->addEventListener(FormEvents::PRE_SET_DATA, $listener)
            ->add('avatar')
            ->addEventListener(FormEvents::PRE_SET_DATA, $listener2)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
