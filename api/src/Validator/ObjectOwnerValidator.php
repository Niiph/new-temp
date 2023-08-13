<?php
/**
 * This file is part of the Diagla package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Validator;

use App\Entity\OwnerInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ObjectOwnerValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    /** @param ObjectOwner $constraint */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value || !is_string($value)) {
            return;
        }

        /** @var UserInterface $user */
        $user = $this->security->getUser();

        $object = $this->entityManager->getRepository($constraint->class)->find($value);

        if (!$object instanceof OwnerInterface || !$object->isOwner($user)) {
            $this->context->buildViolation('app.violation.not_object_owner')->addViolation();
        }
    }
}
