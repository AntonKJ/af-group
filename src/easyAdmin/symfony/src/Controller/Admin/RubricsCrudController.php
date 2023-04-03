<?php

namespace App\Controller\Admin;

use App\Entity\Rubrics;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RubricsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rubrics::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
