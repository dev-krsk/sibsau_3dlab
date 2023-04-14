<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\InvalidEntityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueFieldValidator extends \Symfony\Component\Validator\ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueField) {
            throw new UnexpectedTypeException($constraint, UniqueField::class);
        }

        $entity = $this->context->getObject();

        $repository = $this->entityManager->getRepository(\get_class($entity));

        if (!$repository instanceof EntityRepository) {
            throw new InvalidEntityRepository();
        }

        $object = $repository->findOneBy([
            $this->context->getPropertyName() => $value,
        ]);

        if ($object instanceof $entity && $object->getId() !== $entity->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(UniqueField::ALREADY_EXISTS)
                ->setInvalidValue($value)
                ->atPath($this->context->getPropertyName())
                ->addViolation();
        }
    }
}