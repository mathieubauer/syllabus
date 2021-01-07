<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\Module;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // ADMIN

            // ->add('name', TextType::class)
            // ->add('lectureHours', IntegerType::class)

            // ->add('teachingProfessor', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'email',
            //     'multiple' => 'true',
            //     'expanded' => 'true',
            // ])

            // ->add('module', EntityType::class, [
            //     'class' => Module::class,
            //     'choice_label' => 'name',
            // ])

            // PROF

            ->add('learningOutcomes', CKEditorType::class, [
                'config_name' => 'user_config',
                'config' => [
                    'uiColor' => '#ffffff'
                ]
            ])

            ->add('sessions', CollectionType::class, [
                'entry_type' => SessionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])

            ->add('assessments', CollectionType::class, [
                'entry_type' => AssessmentType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])

            ->add('eLearning')
            ->add('textbook')
            ->add('bibliography')
            ->add('linksWithCompanies')
            ->add('teachingMethods')


            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->add('save_and_add_session', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-primary']
            ])
            ->add('save_and_add_assessment', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
