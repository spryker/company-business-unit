<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper\CompanyBusinessUnitMapper;
use SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitPersistenceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Persistence
 * @group Propel
 * @group Mapper
 * @group CompanyBusinessUnitMapperTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitMapperTest extends Unit
{
    protected const int ID_COMPANY_BUSINESS_UNIT = 1;

    protected const int FK_COMPANY = 2;

    protected const int FK_PARENT_COMPANY_BUSINESS_UNIT = 3;

    protected const int ID_DEFAULT_BILLING_ADDRESS = 4;

    protected const string COMPANY_BUSINESS_UNIT_NAME = 'Head Office';

    protected const string COMPANY_BUSINESS_UNIT_KEY = 'company-business-unit-key';

    protected const string COMPANY_BUSINESS_UNIT_IBAN = 'DE02120300000000202051';

    protected const string COMPANY_BUSINESS_UNIT_BIC = 'BYLADEM1001';

    protected const string COMPANY_BUSINESS_UNIT_EMAIL = 'head.office@spryker.local';

    protected const string COMPANY_BUSINESS_UNIT_PHONE = '+49 30 123456';

    protected const string COMPANY_BUSINESS_UNIT_EXTERNAL_URL = 'https://spryker.local/head-office';

    protected CompanyBusinessUnitPersistenceTester $tester;

    public function testGivenTransferCarriesOnlyDefaultBillingAddressWhenMappedToEntityThenUntouchedColumnsKeepTheirValues(): void
    {
        // Arrange
        $companyBusinessUnitEntity = $this->createPersistedCompanyBusinessUnitEntity();
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT)
            ->setDefaultBillingAddress(static::ID_DEFAULT_BILLING_ADDRESS);

        // Act
        $companyBusinessUnitEntity = (new CompanyBusinessUnitMapper())
            ->mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
                $companyBusinessUnitTransfer,
                $companyBusinessUnitEntity,
            );

        // Assert
        $this->assertSame(static::ID_DEFAULT_BILLING_ADDRESS, $companyBusinessUnitEntity->getDefaultBillingAddress());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_NAME, $companyBusinessUnitEntity->getName());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_KEY, $companyBusinessUnitEntity->getKey());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_IBAN, $companyBusinessUnitEntity->getIban());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_BIC, $companyBusinessUnitEntity->getBic());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_EMAIL, $companyBusinessUnitEntity->getEmail());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_PHONE, $companyBusinessUnitEntity->getPhone());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_EXTERNAL_URL, $companyBusinessUnitEntity->getExternalUrl());
        $this->assertSame(static::FK_COMPANY, $companyBusinessUnitEntity->getFkCompany());
        $this->assertSame(
            static::FK_PARENT_COMPANY_BUSINESS_UNIT,
            $companyBusinessUnitEntity->getFkParentCompanyBusinessUnit(),
        );
    }

    public function testGivenTransferSetsPropertyToNullWhenMappedToEntityThenColumnIsCleared(): void
    {
        // Arrange
        $companyBusinessUnitEntity = $this->createPersistedCompanyBusinessUnitEntity();
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT)
            ->setFkParentCompanyBusinessUnit(null)
            ->setPhone(null);

        // Act
        $companyBusinessUnitEntity = (new CompanyBusinessUnitMapper())
            ->mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
                $companyBusinessUnitTransfer,
                $companyBusinessUnitEntity,
            );

        // Assert
        $this->assertNull($companyBusinessUnitEntity->getFkParentCompanyBusinessUnit());
        $this->assertNull($companyBusinessUnitEntity->getPhone());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_NAME, $companyBusinessUnitEntity->getName());
    }

    protected function createPersistedCompanyBusinessUnitEntity(): SpyCompanyBusinessUnit
    {
        return (new SpyCompanyBusinessUnit())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT)
            ->setFkCompany(static::FK_COMPANY)
            ->setFkParentCompanyBusinessUnit(static::FK_PARENT_COMPANY_BUSINESS_UNIT)
            ->setName(static::COMPANY_BUSINESS_UNIT_NAME)
            ->setKey(static::COMPANY_BUSINESS_UNIT_KEY)
            ->setIban(static::COMPANY_BUSINESS_UNIT_IBAN)
            ->setBic(static::COMPANY_BUSINESS_UNIT_BIC)
            ->setEmail(static::COMPANY_BUSINESS_UNIT_EMAIL)
            ->setPhone(static::COMPANY_BUSINESS_UNIT_PHONE)
            ->setExternalUrl(static::COMPANY_BUSINESS_UNIT_EXTERNAL_URL);
    }
}
