<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use TypeError;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group Facade
 * @group CompanyBusinessUnitFacadeTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_NAME = 'TEST_NAME';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    public function testCreateShouldPersistCompanyBusinessUnit(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->buildCompanyBusinessUnitTransfer([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyBusinessUnitTransferCreated = $this->tester->getFacade()
            ->create($companyBusinessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        // Assert
        $this->assertNotNull(
            $this->tester->getFacade()
                ->findCompanyBusinessUnitById($companyBusinessUnitTransferCreated->getIdCompanyBusinessUnit()),
        );
    }

    public function testGetCompanyBusinessUnitByIdShouldReturnTransferObject(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $foundBusinessUnitTransfer = $this->tester->getFacade()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        // Assert
        $this->assertSame($companyBusinessUnitTransfer->getName(), $foundBusinessUnitTransfer->getName());
    }

    public function testGetCompanyBusinessUnitByIdShouldThrowExceptionWhenNoIdCompanyBusinessUnitProvided(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->buildCompanyBusinessUnitTransfer([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Assert
        $this->expectException(TypeError::class);

        // Act
        $this->tester->getFacade()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }

    public function testFindCompanyBusinessUnitByIdShouldReturnTransferObject(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $actualCompanyBusinessUnitTransfer = $this->tester->getFacade()
            ->findCompanyBusinessUnitById($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        // Assert
        $this->assertSame($companyBusinessUnitTransfer->getName(), $actualCompanyBusinessUnitTransfer->getName());
    }

    public function testFindCompanyBusinessUnitByIdShouldReturnNull(): void
    {
        // Arrange
        $idCompanyBusinessUnit = -1;

        // Act
        $companyBusinessUnitTransfer = $this->tester->getFacade()
            ->findCompanyBusinessUnitById($idCompanyBusinessUnit);

        // Assert
        $this->assertNull($companyBusinessUnitTransfer);
    }

    public function testGetCustomerCompanyBusinessUnitTreeShouldReturnNodesCollection(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $businessUnitTransfer->getFkCompany(),
        ]);
        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $companyBusinessUnitTreeNodeCollectionTransfer = $this->tester->getFacade()
            ->getCustomerCompanyBusinessUnitTree($customerTransfer);

        // Assert
        $this->assertSame(1, count($companyBusinessUnitTreeNodeCollectionTransfer->getCompanyBusinessUnitTreeNodes()));
    }

    public function testUpdateShouldPersistCompanyBusinessUnitChanges(): void
    {
        // Arrange
        $companyBusinessUnitTransferOriginal = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->fromArray($companyBusinessUnitTransferOriginal->toArray());
        $companyBusinessUnitTransfer->setName(static::TEST_NAME);

        // Act
        $companyBusinessUnitResponseTransfer = $this->tester->getFacade()
            ->update($companyBusinessUnitTransfer);

        $actualCompanyBusinessUnitTransfer = $this->tester->getFacade()
            ->findCompanyBusinessUnitById($companyBusinessUnitTransferOriginal->getIdCompanyBusinessUnit());

        // Assert
        $this->assertTrue($companyBusinessUnitResponseTransfer->getIsSuccessful());
        $this->assertSame(static::TEST_NAME, $actualCompanyBusinessUnitTransfer->getName());
    }

    public function testDeleteShouldRemoveCompanyBusinessUnitFromStorage(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $this->tester->getFacade()
            ->delete($companyBusinessUnitTransfer);

        // Assert
        $this->assertNull(
            $this->tester->getFacade()
                ->findCompanyBusinessUnitById($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
        );
    }

    public function testAssignDefaultBusinessUnitToCompanyUserShouldAssignFkCompanyBusinessUnitIfIsNotSet(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyUserTransfer = (new CompanyUserTransfer())->setFkCompany($companyBusinessUnitTransfer->getFkCompany());
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())->setCompanyUser($companyUserTransfer);

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->assignDefaultBusinessUnitToCompanyUser($companyUserResponseTransfer);

        // Assert
        $this->assertSame(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            $companyUserResponseTransfer->getCompanyUser()->getFkCompanyBusinessUnit(),
        );
    }

    public function testBusinessUnitParentIsSaved(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransferChild = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        // Act
        $loadedChildBusinessUnitTransfer = $this->tester->getFacade()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransferChild);

        // Assert
        $this->assertSame(
            $loadedChildBusinessUnitTransfer->getParentCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );
    }

    public function testBusinessUnitCanBeUpdated(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $seedData = [
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
        ];
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit($seedData);
        $businessUnitTransfer->setCompany($companyTransfer);

        // Act
        $loadedChildBusinessUnitTransfer = $this->tester->getFacade()
            ->update($businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        // Assert
        $this->assertSame(
            $loadedChildBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            $businessUnitTransfer->getIdCompanyBusinessUnit(),
        );
        $this->assertSame(
            $loadedChildBusinessUnitTransfer->getFkCompany(),
            $businessUnitTransfer->getFkCompany(),
        );
    }

    public function testBusinessUnitRelationCanBeAddedToExistingUnit(): void
    {
        // Arrange
        $companyBusinessUnitTransferParent = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransferChild = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyBusinessUnitTransferParent->getFkCompany(),
            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
        ]);

        // Act
        $companyBusinessUnitTransferChild->setFkParentCompanyBusinessUnit($companyBusinessUnitTransferParent->getIdCompanyBusinessUnit());
        $this->tester->getFacade()
            ->update($companyBusinessUnitTransferChild);
        $loadedChildBusinessUnitTransfer = $this->tester->getFacade()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransferChild);

        // Assert
        $this->assertSame(
            $loadedChildBusinessUnitTransfer->getParentCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $companyBusinessUnitTransferParent->getIdCompanyBusinessUnit(),
        );
    }

    /**
     * @group Propel
     *
     * @return void
     */
    public function testParentBusinessUnitRelationCanBeSaved(): void
    {
        // Arrange
        $companyBusinessUnitTransferParent = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransferChild = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyBusinessUnitTransferParent->getFkCompany(),
            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransferParent->getIdCompanyBusinessUnit(),
        ]);

        // Act
        $this->tester->getFacade()
            ->update($companyBusinessUnitTransferChild);
        $companyBusinessUnitTransferChildLoaded = $this->tester
            ->getFacade()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransferChild);

        // Assert
        $this->assertSame(
            $companyBusinessUnitTransferChildLoaded->getParentCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $companyBusinessUnitTransferChildLoaded->getFkParentCompanyBusinessUnit(),
        );
        $this->assertSame(
            $companyBusinessUnitTransferChildLoaded->getFkParentCompanyBusinessUnit(),
            $companyBusinessUnitTransferParent->getIdCompanyBusinessUnit(),
        );
    }

    public function testDeleteShouldClearParentForChildrenBusinessUnit(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $seedData = [
            CompanyBusinessUnitTransfer::FK_COMPANY => $businessUnitTransfer->getFkCompany(),
            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $businessUnitTransfer->getIdCompanyBusinessUnit(),
        ];
        $childBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit($seedData);

        // Act
        $this->tester->getFacade()
            ->delete($businessUnitTransfer);
        $loadedChildBusinessUnitTransfer = $this->tester->getFacade()
            ->getCompanyBusinessUnitById($childBusinessUnitTransfer);

        // Assert
        $this->assertNull(
            $loadedChildBusinessUnitTransfer->getFkParentCompanyBusinessUnit(),
        );
    }

    public function testIsUniqueCompanyUserByCustomerShouldReturnFalseIfCompanyUserRelationAlreadyExists(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            CompanyUserTransfer::FK_COMPANY => $businessUnitTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $companyUserTransfer->setIdCompanyUser(null);

        // Act
        $existsCompanyUser = $this->tester->getFacade()
            ->isUniqueCompanyUserByCustomer($companyUserTransfer);

        // Assert
        $this->assertFalse($existsCompanyUser->getIsSuccessful());
    }

    public function testIsUniqueCompanyUserByCustomerShouldReturnTrueToUpdateItself(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            CompanyUserTransfer::FK_COMPANY => $businessUnitTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        // Act
        $existsCompanyUser = $this->tester->getFacade()
            ->isUniqueCompanyUserByCustomer($companyUserTransfer);

        // Assert
        $this->assertTrue($existsCompanyUser->getIsSuccessful());
    }

    public function testIsUniqueCompanyUserByCustomerShouldReturnTrueIfFkCompanyBusinessUnitIsEmpty(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $notExistentCompanyUserTransfer = (new CompanyUserBuilder())
            ->build()
            ->setFkCustomer($customerTransfer->getIdCustomer());

        // Act
        $existsCompanyUser = $this->tester->getFacade()
            ->isUniqueCompanyUserByCustomer($notExistentCompanyUserTransfer);

        // Assert
        $this->assertTrue($existsCompanyUser->getIsSuccessful());
    }

    public function testIsUniqueCompanyUserByCustomerShouldReturnTrueIfFkCustomerIsEmpty(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $notExistentCompanyUserTransfer = (new CompanyUserBuilder())
            ->build()
            ->setFkCompanyBusinessUnit($businessUnitTransfer->getFkCompany());

        // Act
        $existsCompanyUser = $this->tester->getFacade()
            ->isUniqueCompanyUserByCustomer($notExistentCompanyUserTransfer);

        // Assert
        $this->assertTrue($existsCompanyUser->getIsSuccessful());
    }

    public function testIsUniqueCompanyUserByCustomerShouldReturnTrueIfCompanyUserRelationDoesNotExists(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $customerTransfer = $this->tester->haveCustomer();

        $notExistentCompanyUserTransfer = (new CompanyUserBuilder())
            ->build()
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setFkCompanyBusinessUnit($businessUnitTransfer->getFkCompany());

        // Act
        $existsCompanyUser = $this->tester->getFacade()
            ->isUniqueCompanyUserByCustomer($notExistentCompanyUserTransfer);

        // Assert
        $this->assertTrue($existsCompanyUser->getIsSuccessful());
    }

    public function testGetCompanyBusinessUnitCollectionShouldReturnTransferObject(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($businessUnitTransfer->getFkCompany());

        // Act
        $companyBusinessUnitCollection = $this->tester->getFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertGreaterThan(0, $companyBusinessUnitCollection->getCompanyBusinessUnits()->count());
    }

    public function testFindDefaultBusinessUnitByCompanyIdShouldReturnNull(): void
    {
        // Arrange
        $idCompany = -1;

        // Act
        $companyBusinessUnitTransfer = $this->tester->getFacade()->findDefaultBusinessUnitByCompanyId($idCompany);

        // Assert
        $this->assertNull($companyBusinessUnitTransfer);
    }

    public function testUpdateShouldPersistNullablePhoneNumber(): void
    {
        // Arrange
        $companyBusinessUnitTransferOriginal = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->fromArray($companyBusinessUnitTransferOriginal->toArray());
        $companyBusinessUnitTransfer->setPhone(null);

        // Act
        $this->tester->getFacade()->update($companyBusinessUnitTransfer);
        $companyBusinessUnitTransfer = $this->tester->getFacade()->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        // Assert
        $this->assertNotNull($companyBusinessUnitTransferOriginal->getPhone());
        $this->assertNull($companyBusinessUnitTransfer->getPhone());
    }
}
