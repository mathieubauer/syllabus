<?php

namespace App\Controller\Admin;

use App\Entity\Assessment;
use App\Entity\LearningObjective;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AssessmentCrudController extends AbstractCrudController
{

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Assessment::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $learningObjectives = $this->em->getRepository(LearningObjective::class)->findBy([
            'isTitle' => false,
        ]);

        return [
            AssociationField::new('course')->setFormTypeOptions(['choice_label' => 'name']),
            ChoiceField::new('method')->setChoices([
                'Individual Project Assignment' => 'Individual Project Assignment',
                'Group Project Assignment' => 'Group Project Assignment',
                'Individual Presentation' => 'Individual Presentation',
                'Group Presentation' => 'Group Presentation',
                'Written Test' => 'Written Test',
                'Other' => 'Other',
            ]),
            NumberField::new('duration')->setHelp("Indiquer la durée en contact hours (devoir sur table, exposés)"),
            TextEditorField::new('description'),
            ChoiceField::new('stage')->setChoices([
                'Mid Term' => 'Mid Term',
                'Final' => 'Final',
            ]),
            AssociationField::new('learningObjectives')->setFormTypeOptions([
                    'choice_label' => 'description',
                    'choices' => $learningObjectives,
            ]),
            BooleanField::new('isSupervised'),
        ];
    }
}
