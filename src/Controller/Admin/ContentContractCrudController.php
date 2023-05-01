<?php

namespace App\Controller\Admin;

use App\Entity\ContentContract;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

class ContentContractCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContentContract::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('labWork', 'crud.content_contract.field.lab_work')
            ->onlyOnForms()
            ->setRequired(true)
            ->setColumns(12)
            ->setFormTypeOptions([
                'attr' => [
                    'onchange' => 'changeLabWork(this)'
                ]
            ])
        ;

        yield BooleanField::new('parent', 'crud.content_contract.field.parent')
            ->onlyOnForms()
            ->setColumns(12)
            ->setFormTypeOptions([
                'attr' => [
                    'onchange' => 'updateDate(this)'
                ]
            ])
        ;

        yield DateField::new('createdAt', 'crud.content_contract.field.created_at')
            ->onlyOnForms()
            ->setColumns(12);

        yield DateField::new('removedAt', 'crud.content_contract.field.removed_at')
            ->onlyOnForms()
            ->setColumns(12);
    }
}
