<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyBusinessUnitRepositoryInterface
{
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer;

    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer;

    public function hasUsers(int $idCompanyBusinessUnit): bool;

    public function findDefaultBusinessUnitByCompanyId(int $idCompany): ?CompanyBusinessUnitTransfer;

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<string>
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    public function hasCompanyUserByCustomer(CompanyUserTransfer $companyUserTransfer): bool;

    public function findCompanyBusinessUnitById(int $idCompanyBusinessUnit): ?CompanyBusinessUnitTransfer;

    public function findCompanyBusinessUnitByUuid(string $companyBusinessUnitUuid): ?CompanyBusinessUnitTransfer;

    /**
     * @param list<int> $companyUserIds
     *
     * @return array<int, string>
     */
    public function getCompanyBusinessUnitNamesIndexedByCompanyUserIds(array $companyUserIds): array;
}
