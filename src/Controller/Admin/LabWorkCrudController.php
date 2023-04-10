<?php

namespace App\Controller\Admin;

use App\Entity\LabWork;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class LabWorkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LabWork::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('crud.lab_work.singular')
            ->setEntityLabelInPlural('crud.lab_work.plural');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'crud.lab_work.field.id')->hideOnForm();

        yield TextField::new('system_name', 'crud.lab_work.field.system_name')
            ->setRequired(true)
            ->setFormTypeOption('attr', [
                'maxlength' => 40,
                'minlength' => 3
            ])
            ->setFormTypeOption('constraints', [
                new Length(['max' => 40, 'min' => 3])
            ]);

        yield TextField::new('visible_name', 'crud.lab_work.field.visible_name')
            ->setRequired(true)
            ->setFormTypeOption('attr', [
                'maxlength' => 255,
                'minlength' => 3
            ])
            ->setFormTypeOption('constraints', [
                new Length(['max' => 255, 'min' => 3])
            ]);


        yield TextField::new('description', 'crud.lab_work.field.description')
            ->onlyOnIndex()
            ->setMaxLength(30);
        yield TextareaField::new('description', 'crud.lab_work.field.description')
            ->onlyOnDetail();
        yield TextField::new('description', 'crud.lab_work.field.description')
            ->onlyOnForms()
            ->setRequired(false)
            ->setFormTypeOption('attr', [
                'maxlength' => 255
            ])
            ->setFormTypeOption('constraints', [
                new Length(['max' => 255])
            ]);
    }
}
