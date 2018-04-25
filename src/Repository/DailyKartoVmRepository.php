<?php

namespace App\Repository;


use App\Entity\DailyKartoVm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DailyKartoVmRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DailyKartoVm::class);
    }

    /**
     * @param string $size
     * @param string $provider
     * @param string $type
     * @param string $field
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findArevageBySizeAndProvider(string $size, string $provider, string $type, string $field)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select("SUM(dkv.{$field})")
            ->from('App:DailyKartoVm','dkv')
            ->where('dkv.size = :size')
            ->andWhere('dkv.provider = :provider')
            ->andWhere('dkv.type = :type')
            ->setParameter('size', $size)
            ->setParameter('provider', $provider)
            ->setParameter('type', $type)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}