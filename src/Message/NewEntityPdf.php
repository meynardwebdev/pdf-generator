<?php

namespace App\Message;

final class NewEntityPdf
{
     public function __construct(
         private int $entityId
     ) {
     }

    public function getEntityId(): int
    {
        return $this->entityId;
    }
}
