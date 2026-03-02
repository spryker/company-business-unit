<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnit\Zed;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\CompanyBusinessUnit\Dependency\Client\CompanyBusinessUnitToZedRequestClientInterface;

class CompanyBusinessUnitStub implements CompanyBusinessUnitStubInterface
{
    /**
     * @var \Spryker\Client\CompanyBusinessUnit\Dependency\Client\CompanyBusinessUnitToZedRequestClientInterface
     */
    protected $zedRequestClient;

    public function __construct(CompanyBusinessUnitToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer */
        $companyBusinessUnitTransfer = $this->zedRequestClient->call(
            '/company-business-unit/gateway/get-company-business-unit-by-id',
            $companyBusinessUnitTransfer,
        );

        return $companyBusinessUnitTransfer;
    }

    public function createCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer */
        $companyBusinessUnitResponseTransfer = $this->zedRequestClient->call(
            '/company-business-unit/gateway/create',
            $companyBusinessUnitTransfer,
        );

        return $companyBusinessUnitResponseTransfer;
    }

    public function updateCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer */
        $companyBusinessUnitResponseTransfer = $this->zedRequestClient->call(
            '/company-business-unit/gateway/update',
            $companyBusinessUnitTransfer,
        );

        return $companyBusinessUnitResponseTransfer;
    }

    public function deleteCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer */
        $companyBusinessUnitResponseTransfer = $this->zedRequestClient->call(
            '/company-business-unit/gateway/delete',
            $companyBusinessUnitTransfer,
        );

        return $companyBusinessUnitResponseTransfer;
    }

    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer */
        $companyBusinessUnitCollectionTransfer = $this->zedRequestClient->call(
            '/company-business-unit/gateway/get-company-business-unit-collection',
            $companyBusinessUnitCriteriaFilterTransfer,
        );

        return $companyBusinessUnitCollectionTransfer;
    }

    public function getCustomerCompanyBusinessUnitTree(CustomerTransfer $customerTransfer): CompanyBusinessUnitTreeNodeCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer $companyBusinessUnitTreeNodeCollection */
        $companyBusinessUnitTreeNodeCollection = $this->zedRequestClient->call(
            '/company-business-unit/gateway/get-customer-company-business-unit-tree',
            $customerTransfer,
        );

        return $companyBusinessUnitTreeNodeCollection;
    }

    public function findCompanyBusinessUnitByUuid(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer */
        $companyBusinessUnitResponseTransfer = $this->zedRequestClient->call(
            '/company-business-unit/gateway/find-company-business-unit-by-uuid',
            $companyBusinessUnitTransfer,
        );

        return $companyBusinessUnitResponseTransfer;
    }
}
