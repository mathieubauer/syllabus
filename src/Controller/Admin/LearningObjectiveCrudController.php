<?php

namespace App\Controller\Admin;

use App\Entity\LearningObjective;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LearningObjectiveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LearningObjective::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('code'),
            TextareaField::new('description'),
            IntegerField::new('displayOrder'),
            BooleanField::new('isTitle'),
        ];
    }
}
