<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const COMPANY_BUSINESS_UNIT_DEFAULT_NAME = 'Headquarters';

    public const string PARENT_BUSINESS_UNIT_ALIAS = 'parentCompanyBusinessUnit';

    protected const string FILTER_FIELD_NAME = 'name';

    protected const string SORT_FIELD_COMPANY_NAME = 'companyName';

    protected const string SORT_FIELD_PARENT_NAME = 'parentName';

    /**
     * @api
     *
     * @return string
     */
    public function getCompanyBusinessUnitDefaultName(): string
    {
        return static::COMPANY_BUSINESS_UNIT_DEFAULT_NAME;
    }

    /**
     * Specification:
     * - Returns the sortable fields of the company business unit collection, mapped to the column
     *   each one orders by.
     * - Keys are the field names a caller may sort by; values are fully qualified columns, so the
     *   two joined names are sortable alongside the business unit's own.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCompanyBusinessUnitCollectionSortableFieldMap(): array
    {
        return [
            CompanyBusinessUnitTransfer::NAME => SpyCompanyBusinessUnitTableMap::COL_NAME,
            static::SORT_FIELD_COMPANY_NAME => SpyCompanyTableMap::COL_NAME,
            static::SORT_FIELD_PARENT_NAME => static::PARENT_BUSINESS_UNIT_ALIAS . '.name',
        ];
    }

    /**
     * Specification:
     * - Returns the map of filterable field names to the criteria filter properties they set.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCompanyBusinessUnitCollectionFilterableFieldMap(): array
    {
        return [
            static::FILTER_FIELD_NAME => CompanyBusinessUnitCriteriaFilterTransfer::NAME,
        ];
    }
}
