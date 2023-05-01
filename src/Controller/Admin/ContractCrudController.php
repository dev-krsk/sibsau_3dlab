<?php

namespace App\Controller\Admin;

use App\Entity\ContentContract;
use App\Entity\Contract;
use App\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

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
            ->setRequired(true)
            ->setFormTypeOptions([
                'attr' => [
                    'onchange' => 'updateDate(this)'
                ]
            ]);
        yield DateField::new('createdAt', 'crud.contract.field.created_at')
            ->hideOnForm()
            ->setFormat('d.MM.Y')
            ->formatValue(function ($value) {
                return $value ?? 'Пусто';
            })
            ->renderAsText();

        yield DateField::new('removedAt', 'crud.contract.field.removed_at')
            ->onlyOnForms()
            ->setFormTypeOptions([
                'attr' => [
                    'onchange' => 'updateDate(this)'
                ]
            ]);
        yield DateField::new('removedAt', 'crud.contract.field.removed_at')
            ->hideOnForm()
            ->setFormat('d.MM.Y')
            ->formatValue(function ($value) {
                return $value ?? '---';
            })
            ->renderAsText();

        yield FormField::addPanel('crud.contract.field.contents');

        yield CollectionField::new('contents')
            ->onlyOnForms()
            ->useEntryCrudForm()
            ->setEntryIsComplex()
            ->setLabel(false);

        yield CollectionField::new('contents', 'crud.contract.field.contents')
            ->onlyOnIndex()
            ->formatValue(function ($value) {
                $value = \array_filter(\explode('), ', $value), fn($v) => !empty($v));

                if (count($value) == 0)
                    return '';


                return '<span class="badge badge-secondary">' . \implode(')</span><span class="badge badge-secondary">', $value) . '</span>';
            });
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        $this->updateContentContract($formBuilder);

        return $formBuilder;
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        $this->updateContentContract($formBuilder);

        return $formBuilder;
    }

    protected function updateContentContract(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            foreach ($data['contents'] as $key => $content) {
                if (!empty($data['contents'][$key]['parent'])) {
                    $data['contents'][$key]['createdAt'] = $data['createdAt'];
                    $data['contents'][$key]['removedAt'] = $data['removedAt'];
                }
            }

            $event->setData($data);

        });
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addJsFile(Asset::new('js/contract.crud.lib.js')->onlyOnForms());
    }
}
