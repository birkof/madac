<?php

namespace AppBundle\Repository;

/**
 * MeasurementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MeasurementRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllMeasurements()
    {
        return $this->createQueryBuilder('m')->getQuery()->getArrayResult();
    }
}
