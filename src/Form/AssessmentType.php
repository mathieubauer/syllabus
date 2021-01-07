<?php

namespace App\Form;

use App\Entity\Assessment;
use App\Entity\Course;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssessmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('method', ChoiceType::class, [
                'choices' => [
                    'Individual Project Assignment' => 'Individual Project Assignment',
                    'Group Project Assignment' => 'Group Project Assignment',
                    'Individual Presentation' => 'Individual Presentation',
                    'Group Presentation' => 'Group Presentation',
                    'Written Test' => 'Written Test',
                    'Other' => 'Other',
                ]
            ])
            // ->add('duration')
            ->add('stage', ChoiceType::class, [
                'choices' => [
                    'Mid Term' => 'Mid Term',
                    'Final' => 'Final',
                ]
            ])
            // ->add('isSupervised')
            ->add('description', TextareaType::class, [
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
            'data_class' => Assessment::class,
        ]);
    }
}
