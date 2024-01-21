<?php

namespace Qween\Location\Service;

use Doctrine\DBAL\Exception;
use Qween\Location\Component\Geolocator\Location;
use Qween\Location\Dbal\Models\Location as LocationModel;

class LocationService extends AbstractService
{
    /**
     * @throws Exception
     */
    public function findByIp($ip)
    {
        $queryBuilder = $this->queryBuilder;

        $queryBuilder->select('*')->from('locations')
            ->where('ip = :ip')
            ->setParameter('ip', $ip)
            ->executeQuery();

        $result = $this->queryBuilder->fetchAssociative();
        $model = null;
        if (!empty($result)) {
            $model = new LocationModel();
            $model->setId($result['ip']);
            $model->setCountry($result['country_name']);
            $model->setRegion($result['state_prov']);
            $model->setCity($result['city']);
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function save(LocationModel $locate)
    {
        if ($locate) {
            $this->queryBuilder->insert('locations')
                ->values([
                    'ip'           => ':ip',
                    'country_name' => ':country_name',
                    'state_prov'   => ':state_prov',
                    'city'         => ':city',
                ])
                ->setParameters([
                    'ip'           => $locate->getIp(),
                    'country_name' => $locate->getCountry(),
                    'state_prov'   => $locate->getRegion(),
                    'city'         => $locate->getCity(),
                ])
                ->executeQuery();

            return $locate;
        }

        return null;
    }
}