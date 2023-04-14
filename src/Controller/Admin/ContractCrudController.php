<?php

namespace App\Controller\Admin;

use App\Entity\Contract;
use App\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContractCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contract::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('crud.contract.singular')
            ->setEntityLabelInPlural('crud.contract.plural');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'crud.contract.field.id')->hideOnForm();

        yield AssociationField::new('user', 'crud.contract.field.user')
            ->setRequired(true)
            ->autocomplete();

        yield TextField::new('name', 'crud.contract.field.name')
            ->setRequired(true)
            ->setFormTypeOption('attr', [
                'maxlength' => 255,
                'minlength' => 3
            ]);

        yield DateField::new('createdAt', 'crud.contract.field.created_at')
            ->onlyOnForms()
            ->setRequired(true);
        yield DateField::new('createdAt', 'crud.contract.field.created_at')
            ->hideOnForm()
            ->renderAsText();

        yield DateField::new('removedAt', 'crud.contract.field.removed_at')
            ->onlyOnForms();
        yield DateField::new('removedAt', 'crud.contract.field.removed_at')
            ->hideOnForm()
            ->renderAsText();

        yield FormField::addPanel('crud.contract.field.contents');
    }
}
