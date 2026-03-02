<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    public function getCompanyBusinessUnitByIdAction(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        return $this->getFacade()->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }

    public function createAction(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getFacade()->create($companyBusinessUnitTransfer);
    }

    public function updateAction(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getFacade()->update($companyBusinessUnitTransfer);
    }

    public function deleteAction(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getFacade()->delete($companyBusinessUnitTransfer);
    }

    public function getCompanyBusinessUnitCollectionAction(
        CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        return $this->getFacade()->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
    }

    public function getCustomerCompanyBusinessUnitTreeAction(CustomerTransfer $customerTransfer): CompanyBusinessUnitTreeNodeCollectionTransfer
    {
        return $this->getFacade()->getCustomerCompanyBusinessUnitTree($customerTransfer);
    }

    public function findCompanyBusinessUnitByUuidAction(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        return $this->getFacade()->findCompanyBusinessUnitByUuid($companyBusinessUnitTransfer);
    }
}
