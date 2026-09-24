<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitFacade
 * @group FindCompanyBusinessUnitByUuidTest
 * Add your own group annotations below this line
 */
class FindCompanyBusinessUnitByUuidTest extends Unit
{
    /**
     * @var string
     */
    protected const NON_EXISTING_UUID = 'e0e0e0e0-0000-0000-0000-000000000000';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    public function testReturnsTheBusinessUnitMatchingTheUuid(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyBusinessUnitResponseTransfer = $this->tester
            ->getFacade()
            ->findCompanyBusinessUnitByUuid(
                (new CompanyBusinessUnitTransfer())->setUuid($companyBusinessUnitTransfer->getUuid()),
            );

        // Assert
        $this->assertTrue($companyBusinessUnitResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getIdCompanyBusinessUnit(),
        );
    }

    public function testReturnsAnUnsuccessfulResponseWhenNoBusinessUnitMatchesTheUuid(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyBusinessUnitResponseTransfer = $this->tester
            ->getFacade()
            ->findCompanyBusinessUnitByUuid(
                (new CompanyBusinessUnitTransfer())->setUuid(static::NON_EXISTING_UUID),
            );

        // Assert
        $this->assertFalse($companyBusinessUnitResponseTransfer->getIsSuccessful());
        $this->assertNull($companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer());
    }

    /**
     * The uuid behavior the module declares fills the column on save, which is what makes every
     * business unit addressable by uuid without another module supplying the column.
     */
    public function testPersistsAUuidForEveryNewBusinessUnit(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();

        // Act
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Assert
        $this->assertNotNull($companyBusinessUnitTransfer->getUuid());
        $this->assertNotSame('', $companyBusinessUnitTransfer->getUuid());
    }
}
