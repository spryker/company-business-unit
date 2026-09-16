<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\CompanyUser\CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin;
use SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitFacade
 * @group CompanyUserBusinessUnitValidatorTest
 * Add your own group annotations below this line
 */
class CompanyUserBusinessUnitValidatorTest extends Unit
{
    protected const int ID_NON_EXISTENT_COMPANY_BUSINESS_UNIT = 0;

    protected CompanyBusinessUnitTester $tester;

    public function testAcceptsBusinessUnitWhenTheCompanyIdArrivesAsANumericString(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            'fkCompany' => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany((string)$companyTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        // Act
        $companyUserResponseTransfer = (new CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin())
            ->check($companyUserTransfer);

        // Assert
        $this->assertTrue(
            $companyUserResponseTransfer->getIsSuccessful(),
            'A numeric-string company id must not make the company own business unit look like another company\'s.',
        );
    }

    public function testAcceptsBusinessUnitBelongingToTheSameCompany(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            'fkCompany' => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany($companyTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        // Act
        $companyUserResponseTransfer = (new CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin())
            ->check($companyUserTransfer);

        // Assert
        $this->assertTrue($companyUserResponseTransfer->getIsSuccessful());
    }

    public function testRejectsBusinessUnitBelongingToAnotherCompany(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $otherCompanyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            'fkCompany' => $otherCompanyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany($companyTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        // Act
        $companyUserResponseTransfer = (new CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin())
            ->check($companyUserTransfer);

        // Assert
        $this->assertFalse($companyUserResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($companyUserResponseTransfer->getMessages());
    }

    public function testRejectsNonExistentBusinessUnit(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany($companyTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit(static::ID_NON_EXISTENT_COMPANY_BUSINESS_UNIT);

        // Act
        $companyUserResponseTransfer = (new CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin())
            ->check($companyUserTransfer);

        // Assert
        $this->assertFalse($companyUserResponseTransfer->getIsSuccessful());
    }

    public function testAcceptsCompanyUserWithoutBusinessUnitSoTheDefaultAssignmentPluginCanRun(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany($companyTransfer->getIdCompany());

        // Act
        $companyUserResponseTransfer = (new CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin())
            ->check($companyUserTransfer);

        // Assert
        $this->assertTrue($companyUserResponseTransfer->getIsSuccessful());
    }
}
