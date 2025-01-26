<?php

namespace Fulll\Infra\Orm\WriteModel\Manager\Exception;

use Small\Collection\Collection\StringCollection;

class PersistFailException extends \Exception
{

    public StringCollection $reasons;

    public function getReasons(): StringCollection
    {
        return $this->reasons;
    }

    public function setReasons(StringCollection $reasons): PersistFailException
    {
        $this->reasons = $reasons;
        return $this;
    }

}