<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort([
                'module.semester' => 'ASC',
            ])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations principales')->collapsible(),
            TextField::new('name'),

            AssociationField::new('teachingProfessor')
            ->setFormTypeOptions(['choice_label' => 'email'])
            ->formatValue(function ($value, $course) {
                if (count($course->getTeachingProfessor()) == 1) {
                    return $course->getTeachingProfessor()[0]->getEmail();
                }
            })
            ,

            AssociationField::new('module')
            ->setFormTypeOptions(['choice_label' => 'name'])
            ->formatValue(function ($value, $course) {
                return $course->getModule()->getName();
            })
            ,

            NumberField::new('lectureHours'),
            TextEditorField::new('learningOutcomes')->hideOnIndex(),

            FormField::addPanel('Informations pour syllabus')->collapsible(),
            TextEditorField::new('description')->hideOnIndex(),
            TextField::new('language'),


        ];
    }
}
