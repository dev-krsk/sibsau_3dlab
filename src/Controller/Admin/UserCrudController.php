<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('crud.user.singular')
            ->setEntityLabelInPlural('crud.user.plural');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'crud.user.field.id')->hideOnForm();

        yield TextField::new('username', 'crud.user.field.username')->onlyWhenUpdating()->setDisabled();
        yield TextField::new('username', 'crud.user.field.username')->onlyWhenCreating();
        yield TextField::new('username', 'crud.user.field.username')->onlyOnIndex();

        yield EmailField::new('email', 'crud.user.field.email')->onlyOnForms();
        yield TextField::new('email', 'crud.user.field.email')->onlyOnIndex();

        yield ChoiceField::new('roles', 'crud.user.field.roles')
            ->setChoices(array_combine(User::NAME_ROLES, User::SYSTEM_ROLES))
            ->allowMultipleChoices()
            ->renderAsBadges();

        yield TextField::new('password')
            ->onlyWhenCreating()
            ->setRequired(true)
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'crud.user.field.password_new'],
                'second_options' => ['label' => 'crud.user.field.password_repeat'],
                'error_bubbling' => true,
            ]);
        yield TextField::new('password')
            ->onlyWhenUpdating()
            ->setRequired(false)
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'crud.user.field.password_new'],
                'second_options' => ['label' => 'crud.user.field.password_repeat'],
                'error_bubbling' => true,
            ]);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $plainPassword = $entityDto->getInstance()?->getPassword();
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        $this->addEncodePasswordEventListener($formBuilder, $plainPassword);

        return $formBuilder;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        $this->addEncodePasswordEventListener($formBuilder);

        return $formBuilder;
    }

    #[Required]
    public function setEncoder(UserPasswordHasherInterface $passwordHasher): void
    {
        $this->passwordHasher = $passwordHasher;
    }

    protected function addEncodePasswordEventListener(FormBuilderInterface $formBuilder, $plainPassword = null): void
    {
        $formBuilder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($plainPassword) {
            /** @var User $user */
            $user = $event->getData();
            if ($user->getPassword() !== $plainPassword) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            }
        });
    }
}
