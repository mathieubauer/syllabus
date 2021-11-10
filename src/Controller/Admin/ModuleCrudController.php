<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ModuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Module::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations principales')
                // ->setIcon('fa fa-lightbulb')
                ->collapsible(),
            TextField::new('name'),
            AssociationField::new('leader')->setFormTypeOptions(['choice_label' => 'email']),
            AssociationField::new('semester')->setFormTypeOptions(['choice_label' => 'name']),
            NumberField::new('ects'),

            FormField::addPanel('Informations pour syllabus')
                // ->setIcon('fa fa-file-alt'),
                // ->setHelp('Phone number is preferred')
                ->collapsible(),
            TextField::new('code')->hideOnIndex(),
            TextField::new('department')->hideOnIndex(),
            TextField::new('subDepartment')->hideOnIndex(),
            TextField::new('prerequiste')->hideOnIndex(),
            NumberField::new('assessmentMidHours')->hideOnIndex(),
            NumberField::new('assessmentFinalHours')->hideOnIndex(),
            NumberField::new('syncElearningHours')->hideOnIndex(),
            NumberField::new('asyncElearningHours')->hideOnIndex(),
            NumberField::new('selfStudyHours')->hideOnIndex(),
            NumberField::new('projectHours')->hideOnIndex(),
            NumberField::new('otherHours')->hideOnIndex(),

        ];
    }
}
