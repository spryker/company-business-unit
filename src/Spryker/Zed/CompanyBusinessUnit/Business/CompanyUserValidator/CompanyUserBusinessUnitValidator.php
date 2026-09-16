<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyUserValidator;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

class CompanyUserBusinessUnitValidator implements CompanyUserBusinessUnitValidatorInterface
{
    protected const string GLOSSARY_KEY_ERROR_BUSINESS_UNIT_NOT_FOUND = 'message.company_user.validation.business_unit_not_found';

    protected const string GLOSSARY_KEY_ERROR_BUSINESS_UNIT_NOT_IN_COMPANY = 'message.company_user.validation.business_unit_not_in_company';

    public function __construct(
        protected CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository,
    ) {
    }

    public function validateBusinessUnitBelongsToCompany(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);

        $idCompanyBusinessUnit = $companyUserTransfer->getFkCompanyBusinessUnit();

        if ($idCompanyBusinessUnit === null) {
            return $companyUserResponseTransfer;
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitRepository
            ->findCompanyBusinessUnitById($idCompanyBusinessUnit);

        if ($companyBusinessUnitTransfer === null) {
            return $this->prepareErrorCompanyUserResponseTransfer($companyUserResponseTransfer, static::GLOSSARY_KEY_ERROR_BUSINESS_UNIT_NOT_FOUND);
        }

        $idCompany = $companyUserTransfer->getFkCompany();

        if ($idCompany === null || (int)$companyBusinessUnitTransfer->getFkCompany() === (int)$idCompany) {
            return $companyUserResponseTransfer;
        }

        return $this->prepareErrorCompanyUserResponseTransfer($companyUserResponseTransfer, static::GLOSSARY_KEY_ERROR_BUSINESS_UNIT_NOT_IN_COMPANY);
    }

    protected function prepareErrorCompanyUserResponseTransfer(
        CompanyUserResponseTransfer $companyUserResponseTransfer,
        string $glossaryKey
    ): CompanyUserResponseTransfer {
        return $companyUserResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage((new ResponseMessageTransfer())->setText($glossaryKey));
    }
}
