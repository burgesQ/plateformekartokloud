<?php

namespace App\Service;


use App\Entity\KartoVm;
use App\Entity\KartoVmMap;
use Doctrine\ORM\EntityManagerInterface;

class KartoMapCreator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param array $oneKVm
     *
     * @return KartoVmMap|bool
     */
    public function createKartoVmMap(array $oneKVm)
    {
        if (!self::checkArray($oneKVm))
            return false;

        $kvmRepo = $this->entityManager->getRepository(KartoVm::class);

        $vm  = $kvmRepo->findOneBy(["id" => $oneKVm["karto_vm_id"]]);

        if ($vm) {

            $kvm = new KartoVmMap($oneKVm["x_pos"], $oneKVm["y_pos"]);
            $kvm->setKartoVm($vm);

            $this->entityManager->persist($kvm);
            $this->entityManager->flush();

            return $kvm;
        }
        return false;
    }

    private static function checkArray(array $oneKVm)
    {
        return (array_key_exists("x_pos", $oneKVm)
                && array_key_exists("y_pos", $oneKVm)
                && array_key_exists("karto_vm_id", $oneKVm));
    }
}