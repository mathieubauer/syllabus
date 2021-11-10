<?php

namespace App\Controller\Admin;

use App\Entity\Course;
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

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations principales')->collapsible(),
            TextField::new('name'),
            AssociationField::new('teachingProfessor')->setFormTypeOptions(['choice_label' => 'email']),
            AssociationField::new('module')->setFormTypeOptions(['choice_label' => 'name']),
            NumberField::new('lectureHours'),
            TextEditorField::new('learningOutcomes'),

            FormField::addPanel('Informations pour syllabus')->collapsible(),
            TextEditorField::new('description'),
            TextField::new('language'),


        ];
    }
}
