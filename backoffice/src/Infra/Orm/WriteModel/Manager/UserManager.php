<?php

namespace Fulll\Infra\Orm\WriteModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\UserManagerInterface;
use Fulll\Infra\Orm\WriteModel\Entity\User;
use Fulll\Infra\Orm\WriteModel\Manager\Exception\PersistFailException;
use Small\Collection\Collection\StringCollection;
use Small\Forms\Adapter\AnnotationAdapter;
use Small\Forms\Form\FormBuilder;
use Small\Forms\ValidationRule\Exception\ValidationFailException;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;

#[Entity(User::class)]
#[Connection('user', 'writer')]
class UserManager extends AbstractRelationnalManager
    implements UserManagerInterface
{

    public function saveUser(\Fulll\Domain\Entity\User $user): self
    {

        $messages = new StringCollection();
        try {
            /** @var User $ormUser */
            $ormUser = $this->newEntity();
            FormBuilder::createFromAdapter(new AnnotationAdapter($ormUser))
                ->fillFromObject($user)
                ->validate($messages, true)
                ->hydrate($ormUser);
        } catch (ValidationFailException) {
            $messages->map(function (int $i, string $message) {
                echo $message . "\n";
            });
            throw (new PersistFailException('Can\t persist user'))
                ->setReasons($messages);
        }

        $ormUser->persist();

        return $this;

    }

}