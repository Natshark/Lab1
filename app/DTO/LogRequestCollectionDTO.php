<?php

namespace App\DTO;

class LogRequestCollectionDTO
{
    public $log_requests;

    public function __construct(array $log_requests)
    {
        $this->log_requests = $log_requests;
    }

    public static function fromCollectionToDTO($logRequests): self
    {
        $logRequestDTOs = [];

        foreach ($logRequests as $logRequest)
        {
            $logRequestDTOs[] = LogRequestDTO::fromModelToDTO($logRequest);
        }

        return new self($logRequestDTOs);
    }
}
