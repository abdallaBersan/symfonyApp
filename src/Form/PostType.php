<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Image',
                'required' => false, // => l'image n'est pas obligatoire
                'mapped' => false // pour ne pas l'enregistrer dans le tableau
            ))
            ->add('category', EntityType::class, array( //EntityType est une liste deroulante
                'label' => 'Choisir la catégorie',
                'class' => Category::class, // d'ou vient cette liste
                'choice_label' => 'title'
            ))
            ->add('title', TextType::class, array(
                'label' => 'Titre de la catégorie',
                'attr' => [
                    'placeholder' => 'Entrez le text'
                ]
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Déscription de la catégorie',
                'attr' => [
                    'placeholder' => 'Entrez la déscription'
                ]
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success float-left mr-2'
                ]
            ))
            ->add('delete', SubmitType::class, array(
                'label' => 'Supprimer',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
