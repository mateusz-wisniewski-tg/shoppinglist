<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait TimestampableTrait
{
    #[PrePersist]
    public function onPrePersist()
    {
        $this->createdAt = new \DateTimeImmutable("now");
        $this->updatedAt = new \DateTimeImmutable("now");
    }

    #[PreUpdate]
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTimeImmutable("now");
    }
}
