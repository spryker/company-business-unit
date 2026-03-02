<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Orm\Zed\Company\Persistence\SpyCompany;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;

class CompanyBusinessUnitMapper implements CompanyBusinessUnitMapperInterface
{
    public function mapBusinessUnitTransferToEntityTransfer(
        CompanyBusinessUnitTransfer $businessUnitTransfer,
        SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer
    ): SpyCompanyBusinessUnitEntityTransfer {
        $businessUnitEntityTransfer->fromArray($businessUnitTransfer->modifiedToArray(), true);
        $businessUnitEntityTransfer->setCompany(null);
        $businessUnitEntityTransfer->setParentCompanyBusinessUnit(null);

        return $businessUnitEntityTransfer;
    }

    public function mapEntityTransferToBusinessUnitTransfer(
        SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer,
        CompanyBusinessUnitTransfer $businessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $businessUnitTransfer->fromArray($businessUnitEntityTransfer->toArray(), true);
        if (!$businessUnitTransfer->getFkParentCompanyBusinessUnit()) {
            $businessUnitTransfer->setParentCompanyBusinessUnit(null);
        }

        return $businessUnitTransfer;
    }

    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $companyBusinessUnitTransfer->fromArray(
            $companyBusinessUnitEntity->toArray(),
            true,
        );
        $companyBusinessUnitTransfer->setCompany($this->mapCompanyEntityToCompanyTransfer(
            $companyBusinessUnitEntity->getCompany(),
            new CompanyTransfer(),
        ));

        return $companyBusinessUnitTransfer;
    }

    public function mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        SpyCompanyBusinessUnit $companyBusinessUnitEntity
    ): SpyCompanyBusinessUnit {
        return $companyBusinessUnitEntity->fromArray($companyBusinessUnitTransfer->toArray());
    }

    protected function mapCompanyEntityToCompanyTransfer(
        SpyCompany $companyEntity,
        CompanyTransfer $companyTransfer
    ): CompanyTransfer {
        return $companyTransfer->fromArray(
            $companyEntity->toArray(),
            true,
        );
    }
}
