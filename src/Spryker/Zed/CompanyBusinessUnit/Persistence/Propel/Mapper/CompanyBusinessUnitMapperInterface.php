<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;

interface CompanyBusinessUnitMapperInterface
{
    public function mapBusinessUnitTransferToEntityTransfer(
        CompanyBusinessUnitTransfer $businessUnitTransfer,
        SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer
    ): SpyCompanyBusinessUnitEntityTransfer;

    public function mapEntityTransferToBusinessUnitTransfer(
        SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer,
        CompanyBusinessUnitTransfer $businessUnitTransfer
    ): CompanyBusinessUnitTransfer;

    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer;

    public function mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        SpyCompanyBusinessUnit $companyBusinessUnitEntity
    ): SpyCompanyBusinessUnit;
}
