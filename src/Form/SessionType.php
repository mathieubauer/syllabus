<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Course;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('duration')
            // ->add('theme', CKEditorType::class)
            // ->add('bibliographyReferences', CKEditorType::class)
            // ->add('assignment', CKEditorType::class)
            ->add('theme', TextareaType::class, [
                'required' => false
            ])
            ->add('bibliographyReferences', TextareaType::class, [
                'required' => false
            ])
            ->add('assignment', TextareaType::class, [
                'required' => false
            ])
            // ->add('course', EntityType::class, [
            //     'class' => Course::class,
            //     'choice_label' => 'name',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
