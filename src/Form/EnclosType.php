<?php

namespace App\Form;

use App\Entity\Enclos;
use App\Entity\Espace;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnclosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('superficie')
            ->add('maxAnimaux')
            ->add('quarentaine')
            ->add('espace_id', EntityType::class, [
                "class" => Espace::class,
                "choice_label" => "nom",
                "multiple" => false,
                "expanded" => false,
            ])
            ->add("OK", SubmitType::class, ["label" => "Ajouter"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enclos::class,
        ]);
    }
}
