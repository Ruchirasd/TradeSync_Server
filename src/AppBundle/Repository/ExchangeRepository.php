<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ExchangeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExchangeRepository extends EntityRepository
{
    public function getExchange($exchangeCode){
        $dql="SELECT exchange FROM AppBundle\Entity\Exchange exchange WHERE exchange.code='$exchangeCode' ";
        $query = $this->getEntityManager()->createQuery($dql)->setMaxResults(1);
        $result=$query->getResult()[0];
        return $result;
    }
}
