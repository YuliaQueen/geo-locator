<?php

namespace Qween\Location\Service;

use Doctrine\DBAL\Query\QueryBuilder;
use Qween\Location\Dbal\EntityService;

abstract class AbstractService
{
    public function __construct(
        protected EntityService $entityService,
        protected QueryBuilder  $queryBuilder
    )
    {
        $this->queryBuilder = $this->entityService->getConnection()->createQueryBuilder();
    }
}