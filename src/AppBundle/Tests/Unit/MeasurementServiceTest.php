<?php

namespace AppBundle\Tests\Unit;

use AppBundle\Service\MeasurementService;
use AppBundle\Tests\Base\UnitTest;
use Doctrine\ORM\EntityManager;

class MeasurementServiceTest extends UnitTest
{
    /**
     * @var MeasurementService
     */
    protected $measurementService;

    public function setUp()
    {
        parent::setUp();
        $this->measurementService = new MeasurementService($this->getEntityManagerMock());
    }

    /**
     * @return \Mockery\MockInterface|EntityManager
     */
    protected function getEntityManagerMock()
    {
        return \Mockery::mock('Doctrine\ORM\EntityManager');
    }

    public function dataValidateSaveData_WhenEmptyInputData_ThrowsException()
    {
        $testCases = [];

        //Empty array
        $testCases['emptyArray'] = [
            []
        ];

        //Empty string
        $testCases['emptyString'] = [
            ''
        ];

        //Zero
        $testCases['zero'] = [
            0
        ];

        return $testCases;
    }

    /**
     * @dataProvider dataValidateSaveData_WhenEmptyInputData_ThrowsException
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Empty or not an array provided
     * @param $inputData
     */
    public function testValidateSaveData_WhenEmptyInputData_ThrowsException($inputData)
    {
        //Act
        $this->callMethod($this->measurementService, 'validateSaveData', [$inputData]);
    }
}
