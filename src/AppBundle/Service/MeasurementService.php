<?php
namespace AppBundle\Service;

use AppBundle\Entity\Measurement;
use AppBundle\Repository\MeasurementRepository;
use Doctrine\ORM\EntityManager;

class MeasurementService
{
    const ID = 'measurement';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * MeasurementService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $data
     * @throws \InvalidArgumentException
     */
    protected function validateSaveData($data)
    {
        if (empty($data) || !is_array($data)) {
            throw new \InvalidArgumentException("Empty or not an array provided");
        }

        $requiredKeys = [
            'ean',
            'height',
            'width',
            'length'
        ];

        $isError = false;
        $missingKeys = [];
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $data)) {
                array_push($missingKeys, $requiredKey);
                $isError = true;
            }

        }

        if ($isError) {
            $missingKeys = implode(', ', $missingKeys);
            throw new \InvalidArgumentException(
                "Missing required keys: [$missingKeys]"
            );
        }
    }

    /**
     * @param $data
     * @throws \InvalidArgumentException
     */
    public function updateOrInsertMeasurement($data)
    {
        $this->validateSaveData($data);

        $repo = $this->entityManager->getRepository(Measurement::REPOSITORY);
        $measurement = $repo->findBy(['ean' =>  $data['ean']]);

        if (!$measurement) {
            $measurement = new Measurement();
        }

        $measurement->setEan($data['ean']);
        $measurement->setHeight($data['height']);
        $measurement->setWidth($data['width']);
        $measurement->setLength($data['length']);

        $this->computeVolume($measurement);

        $this->entityManager->persist($measurement);
        $this->entityManager->flush();
    }

    public function getMeasurements()
    {
        /** @var MeasurementRepository $measurementsRepo */
        $measurementsRepo = $this->entityManager->getRepository(Measurement::REPOSITORY);
        return $measurementsRepo->getAllMeasurements();
    }

    public function computeVolume(Measurement $measurement)
    {
        $measurement->setVolume($measurement->getHeight() * $measurement->getWidth() * $measurement->getLength());
    }
}
