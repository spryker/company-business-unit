<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserSavePreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitBelongsToCompanyCompanyUserSavePreCheckPlugin extends AbstractPlugin implements CompanyUserSavePreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks that `CompanyUserTransfer.fkCompanyBusinessUnit` resolves to an existing company business unit.
     * - Checks that the company business unit belongs to `CompanyUserTransfer.fkCompany`.
     * - Returns an unsuccessful response with a message when either check fails.
     * - Returns a successful response when `CompanyUserTransfer.fkCompanyBusinessUnit` is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function check(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getBusinessFactory()
            ->createCompanyUserBusinessUnitValidator()
            ->validateBusinessUnitBelongsToCompany($companyUserTransfer);
    }
}
