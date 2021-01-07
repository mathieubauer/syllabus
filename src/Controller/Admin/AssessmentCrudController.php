<?php

namespace App\Controller\Admin;

use App\Entity\Assessment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AssessmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Assessment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('course')->setFormTypeOptions(['choice_label' => 'name']),
            ChoiceField::new('method')->setChoices([
                'Group Presentation' => 'Group Presentation',
                'Group Project Assignment' => 'Group Project Assignment',
                'Written Test' => 'Written Test',
            ]),
            NumberField::new('duration')->setHelp("Indiquer la durée en contact hours (devoir sur table, exposés)"),
            TextEditorField::new('description'),
            ChoiceField::new('stage')->setChoices([
                'Mid Term' => 'Mid Term',
                'Final' => 'Final',
            ]),
            BooleanField::new('isSupervised'),
        ];
    }
}
