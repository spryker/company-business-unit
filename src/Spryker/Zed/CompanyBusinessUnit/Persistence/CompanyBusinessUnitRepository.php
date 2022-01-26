<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 */
class CompanyBusinessUnitRepository extends AbstractRepository implements CompanyBusinessUnitRepositoryInterface
{
    /**
     * @var string
     */
    protected const TABLE_JOIN_PARENT_COMPANY_BUSINESS_UNIT = 'parentCompanyBusinessUnit';

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer {
        $query = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        $criteriaFilterTransfer->requireIdCompany();
        $query = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByFkCompany($criteriaFilterTransfer->getIdCompany());

        if ($criteriaFilterTransfer->getIdCompanyUser() !== null) {
            $query->useCompanyUserQuery()
                ->filterByIdCompanyUser($criteriaFilterTransfer->getIdCompanyUser())
                ->endUse();
        }

        $this->filterCompanyBusinessUnitCollection($query, $criteriaFilterTransfer);

        $collection = $this->buildQueryFromCriteria($query, $criteriaFilterTransfer->getFilter());
        $collection = $this->getPaginatedCollection($collection, $criteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        foreach ($collection as $businessUnitEntity) {
            $businessUnitTransfer = $this->getFactory()
                ->createCompanyBusinessUnitMapper()
                ->mapEntityTransferToBusinessUnitTransfer(
                    $businessUnitEntity,
                    new CompanyBusinessUnitTransfer()
                );

            $collectionTransfer->addCompanyBusinessUnit($businessUnitTransfer);
        }

        $collectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function hasUsers(int $idCompanyBusinessUnit): bool
    {
        $spyCompanyBusinessUnit = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->useCompanyUserQuery()
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->findOne();

        return ($spyCompanyBusinessUnit !== null);
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    public function findDefaultBusinessUnitByCompanyId(int $idCompany): ?CompanyBusinessUnitTransfer
    {
        $query = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByFkCompany($idCompany);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
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

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

            $paginationModel = $query->paginate($page, $maxPerPage);

            $paginationTransfer->setNbResults($paginationModel->getNbResults());
            $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
            $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
            $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
            $paginationTransfer->setLastPage($paginationModel->getLastPage());
            $paginationTransfer->setNextPage($paginationModel->getNextPage());
            $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getResults();
        }

        return $query->find();
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return void
     */
    protected function filterCompanyBusinessUnitCollection(
        SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery,
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        if ($criteriaFilterTransfer->getCompanyBusinessUnitIds()) {
            $companyBusinessUnitQuery->filterByIdCompanyBusinessUnit_In($criteriaFilterTransfer->getCompanyBusinessUnitIds());
        }
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function getSpyCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->leftJoinParentCompanyBusinessUnit(static::TABLE_JOIN_PARENT_COMPANY_BUSINESS_UNIT)
            ->with(static::TABLE_JOIN_PARENT_COMPANY_BUSINESS_UNIT)
            ->innerJoinWithCompany();
    }
}
