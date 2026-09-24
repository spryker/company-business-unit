<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyBusinessUnitHelper extends Module
{
    use LocatorHelperTrait;

    public function haveCompanyBusinessUnit(array $seedData = []): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit(null);

        $this->ensureCompanyBusinessUnitWithKeyDoesNotExist($companyBusinessUnitTransfer->getKey());

        return $this->getCompanyBusinessUnitFacade()
            ->create($companyBusinessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();
    }

    public function haveCompanyNamed(string $name): CompanyTransfer
    {
        $companyTransfer = (new CompanyBuilder([CompanyTransfer::NAME => $name]))->build();
        $companyTransfer->setIdCompany(null);

        return $this->getCompanyFacade()
            ->create($companyTransfer)
            ->getCompanyTransfer();
    }

    public function buildUniqueToken(): string
    {
        return uniqid('cbu', false);
    }

    public function getBusinessUnitsCount(): int
    {
        return SpyCompanyBusinessUnitQuery::create()->count();
    }

    protected function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getLocator()->company()->facade();
    }

    protected function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }

    protected function ensureCompanyBusinessUnitWithKeyDoesNotExist(string $key): void
    {
        $companyBusinessUnitQuery = $this->getCompanyBusinessUnitQuery();
        $companyBusinessUnitQuery->filterByKey($key)->delete();
    }

    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }
}
