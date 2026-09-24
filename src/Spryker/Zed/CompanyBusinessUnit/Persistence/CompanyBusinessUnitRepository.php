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
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitException\CompanyBusinessUnitNotFoundException;
use Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 */
class CompanyBusinessUnitRepository extends AbstractRepository implements CompanyBusinessUnitRepositoryInterface
{
    /**
     * The uuid column reaches spy_company_business_unit through a Propel behavior, so the generated
     * query only gains the filters once the project has run the migration for it.
     */
    protected const string COMPANY_BUSINESS_UNIT_UUID_FILTER_METHOD = 'filterByUuid';

    /**
     * @see static::COMPANY_BUSINESS_UNIT_UUID_FILTER_METHOD
     */
    protected const string COMPANY_BUSINESS_UNIT_UUIDS_FILTER_METHOD = 'filterByUuid_In';

    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_REFERENCE
     *
     * @var string
     */
    protected const COL_CUSTOMER_REFERENCE = 'spy_customer.customer_reference';

    /**
     * @var string
     */
    protected const COL_FK_CUSTOMER = 'fk_customer';

    /**
     * @see \Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap::COL_ID_COMPANY_USER
     */
    protected const string COL_ID_COMPANY_USER = 'spy_company_user.id_company_user';

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @throws \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitException\CompanyBusinessUnitNotFoundException
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer {
        $query = $this->getSpyCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();
        if ($entityTransfer === null) {
            throw new CompanyBusinessUnitNotFoundException(
                sprintf(
                    'Company business unit with ID `%d` not found.',
                    $idCompanyBusinessUnit,
                ),
            );
        }

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        $query = $this->getSpyCompanyBusinessUnitQuery();

        if ($criteriaFilterTransfer->getIdCompany()) {
            $query
                ->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getIdCompanyUser() !== null) {
            $query
                ->useCompanyUserQuery()
                    ->filterByIdCompanyUser($criteriaFilterTransfer->getIdCompanyUser())
                ->endUse();
        }

        $this->filterCompanyBusinessUnitCollection($query, $criteriaFilterTransfer);
        $this->applySearchTermToQuery($query, $criteriaFilterTransfer);
        $this->applySortToQuery($query, $criteriaFilterTransfer);

        $collection = $this->buildQueryFromCriteria($query, $criteriaFilterTransfer->getFilter());
        $collection = $this->getPaginatedCollection($collection, $criteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        foreach ($collection as $businessUnitEntity) {
            $businessUnitTransfer = $this->getFactory()
                ->createCompanyBusinessUnitMapper()
                ->mapEntityTransferToBusinessUnitTransfer(
                    $businessUnitEntity,
                    new CompanyBusinessUnitTransfer(),
                );

            $collectionTransfer->addCompanyBusinessUnit($businessUnitTransfer);
        }

        $collectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    public function hasUsers(int $idCompanyBusinessUnit): bool
    {
        $existsSpyCompanyBusinessUnit = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->useCompanyUserQuery()
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->exists();

        return $existsSpyCompanyBusinessUnit;
    }

    public function findDefaultBusinessUnitByCompanyId(int $idCompany): ?CompanyBusinessUnitTransfer
    {
        $query = $this->getSpyCompanyBusinessUnitQuery()
            ->filterByFkCompany($idCompany);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if (!$entityTransfer) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<string>
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $customerReferences */
        $customerReferences = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->select(static::COL_CUSTOMER_REFERENCE)
            ->filterByIdCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->useCompanyUserQuery()
                ->joinCustomer()
            ->endUse()
            ->find();

         return $customerReferences->toArray();
    }

    public function findCompanyBusinessUnitById(int $idCompanyBusinessUnit): ?CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitQuery = $this->getSpyCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitEntity = $companyBusinessUnitQuery->findOne();

        if (!$companyBusinessUnitEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer($companyBusinessUnitEntity, new CompanyBusinessUnitTransfer());
    }

    public function findCompanyBusinessUnitByUuid(string $companyBusinessUnitUuid): ?CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitQuery = $this->getSpyCompanyBusinessUnitQuery();

        if (!method_exists($companyBusinessUnitQuery, static::COMPANY_BUSINESS_UNIT_UUID_FILTER_METHOD)) {
            return null;
        }

        $companyBusinessUnitEntity = $companyBusinessUnitQuery
            ->filterByUuid($companyBusinessUnitUuid)
            ->findOne();

        if (!$companyBusinessUnitEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
                $companyBusinessUnitEntity,
                new CompanyBusinessUnitTransfer(),
            );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection|array<\Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer>
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer === null) {
            return $query->find();
        }

        $paginationModel = $query->paginate(
            $paginationTransfer->getPageOrFail(),
            $paginationTransfer->getMaxPerPageOrFail(),
        );
        $this->mapPaginationModel($paginationTransfer, $paginationModel);

        return $paginationModel->getResults();
    }

    protected function getSpyCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->leftJoinParentCompanyBusinessUnit(CompanyBusinessUnitConfig::PARENT_BUSINESS_UNIT_ALIAS)
            ->with(CompanyBusinessUnitConfig::PARENT_BUSINESS_UNIT_ALIAS)
            ->innerJoinWithCompany();
    }

    protected function mapPaginationModel(PaginationTransfer $paginationTransfer, PropelModelPager $paginationModel): void
    {
        $paginationTransfer
            ->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());
    }

    protected function applySearchTermToQuery(
        SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery,
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        $searchTerm = $criteriaFilterTransfer->getSearchTerm();

        if ($searchTerm === null || $searchTerm === '') {
            return;
        }

        $pattern = sprintf('%%%s%%', mb_strtolower($searchTerm));

        $companyBusinessUnitQuery
            ->condition('businessUnitName', sprintf('LOWER(%s) LIKE ?', SpyCompanyBusinessUnitTableMap::COL_NAME), $pattern)
            ->condition('companyName', sprintf('LOWER(%s) LIKE ?', SpyCompanyTableMap::COL_NAME), $pattern)
            ->condition('parentName', sprintf('LOWER(%s.name) LIKE ?', CompanyBusinessUnitConfig::PARENT_BUSINESS_UNIT_ALIAS), $pattern)
            ->where(['businessUnitName', 'companyName', 'parentName'], Criteria::LOGICAL_OR);
    }

    protected function applySortToQuery(
        SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery,
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        $sortableFieldMap = $this->getFactory()->getConfig()->getCompanyBusinessUnitCollectionSortableFieldMap();

        foreach ($criteriaFilterTransfer->getSortCollection() as $sortTransfer) {
            $column = $sortableFieldMap[$sortTransfer->getField()] ?? null;

            if ($column === null) {
                continue;
            }

            $companyBusinessUnitQuery->orderBy($column, $sortTransfer->getIsAscending() === false ? Criteria::DESC : Criteria::ASC);
        }

        $companyBusinessUnitQuery->orderBy(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT, Criteria::DESC);
    }

    protected function filterCompanyBusinessUnitCollection(
        SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery,
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        if ($criteriaFilterTransfer->getCompanyBusinessUnitIds()) {
            $companyBusinessUnitQuery->filterByIdCompanyBusinessUnit_In($criteriaFilterTransfer->getCompanyBusinessUnitIds());
        }

        if (
            $criteriaFilterTransfer->getUuids() !== []
            && method_exists($companyBusinessUnitQuery, static::COMPANY_BUSINESS_UNIT_UUIDS_FILTER_METHOD)
        ) {
            $companyBusinessUnitQuery->filterByUuid_In($criteriaFilterTransfer->getUuids());
        }

        if ($criteriaFilterTransfer->getName()) {
            $companyBusinessUnitQuery->where(
                sprintf('LOWER(%s) LIKE ?', SpyCompanyBusinessUnitTableMap::COL_NAME),
                sprintf('%%%s%%', mb_strtolower($criteriaFilterTransfer->getName())),
            );
        }

        if ($criteriaFilterTransfer->getCompanyIds() !== []) {
            $companyBusinessUnitQuery
                ->filterByFkCompany_In($criteriaFilterTransfer->getCompanyIds());
        }

        if ($criteriaFilterTransfer->getIdCompany()) {
            $companyBusinessUnitQuery->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getFilter() && $criteriaFilterTransfer->getFilter()->getLimit()) {
            $companyBusinessUnitQuery->limit($criteriaFilterTransfer->getFilter()->getLimit());
        }
    }

    public function hasCompanyUserByCustomer(CompanyUserTransfer $companyUserTransfer): bool
    {
        $companyUserTransfer
            ->requireFkCompanyBusinessUnit()
            ->requireFkCustomer();

        $companyUserQuery = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->useCompanyUserQuery();

        if (!$companyUserQuery->getTableMap()->hasColumn(static::COL_FK_CUSTOMER)) {
            return false;
        }

        if ($companyUserTransfer->getIdCompanyUser()) {
            $companyUserQuery
                ->filterByIdCompanyUser($companyUserTransfer->getIdCompanyUser(), Criteria::NOT_EQUAL);
        }

        return $companyUserQuery
            ->filterByFkCustomer($companyUserTransfer->getFkCustomer())
            ->endUse()
            ->filterByIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit())
            ->exists();
    }

    /**
     * @param list<int> $companyUserIds
     *
     * @return array<int, string>
     */
    public function getCompanyBusinessUnitNamesIndexedByCompanyUserIds(array $companyUserIds): array
    {
        if ($companyUserIds === []) {
            return [];
        }

        $query = $this->getFactory()->createCompanyBusinessUnitQuery();
        $query
            ->select([
                SpyCompanyBusinessUnitTableMap::COL_NAME,
                static::COL_ID_COMPANY_USER,
            ])
            ->useCompanyUserQuery()
                ->filterByIdCompanyUser_In($companyUserIds)
            ->endUse();

        $result = [];

        foreach ($query->find() as $item) {
            $result[$item[static::COL_ID_COMPANY_USER]] = $item[SpyCompanyBusinessUnitTableMap::COL_NAME];
        }

        return $result;
    }
}
