<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Enclos;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroIdentification', null, [
                'attr' => ["minlenght" => "14", "maxlenght" => "14"]
            ])
            ->add('nom')
            ->add('dateNaissance')
            ->add('dateArrive', DateType::class, [
                'data' => new DateTime("now")
            ])
            ->add('zooProprietaire')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Mâle' => 'Mâle',
                    'Femelle' => 'Femelle',
                    'non déterminé' => 'non déterminé',
                ],
            ])
            ->add('espece')
            ->add('sterile')
            ->add('quarentaine')
            ->add('Enclos', EntityType::class, [
                "class" => Enclos::class,
                "choice_label" => "nom",
                "multiple" => false,
                "expanded" => false,
            ])
            ->add("OK", SubmitType::class, ["label" => "Ajouter"]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
