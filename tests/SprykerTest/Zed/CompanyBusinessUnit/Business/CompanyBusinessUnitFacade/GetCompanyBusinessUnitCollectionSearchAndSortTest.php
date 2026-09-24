<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitFacade
 * @group GetCompanyBusinessUnitCollectionSearchAndSortTest
 * Add your own group annotations below this line
 */
class GetCompanyBusinessUnitCollectionSearchAndSortTest extends Unit
{
    protected const string SORT_FIELD_COMPANY_NAME = 'companyName';

    protected const string SORT_FIELD_PARENT_NAME = 'parentName';

    protected CompanyBusinessUnitTester $tester;

    public function testSearchTermMatchesTheBusinessUnitName(): void
    {
        // Arrange
        [$token, $businessUnit] = $this->haveBusinessUnitTree();

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Child'));

        // Assert
        $this->assertContains($businessUnit->getUuid(), $uuids);
    }

    public function testSearchTermMatchesTheCompanyName(): void
    {
        // Arrange
        [$token, $businessUnit] = $this->haveBusinessUnitTree();

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Company'));

        // Assert
        $this->assertContains($businessUnit->getUuid(), $uuids);
    }

    public function testSearchTermMatchesTheParentBusinessUnitName(): void
    {
        // Arrange
        [$token, $businessUnit] = $this->haveBusinessUnitTree();

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Parent'));

        // Assert
        $this->assertContains($businessUnit->getUuid(), $uuids);
    }

    public function testSearchTermExcludesWhatItDoesNotMatch(): void
    {
        // Arrange
        [, $businessUnit] = $this->haveBusinessUnitTree();

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm('zzz-no-such-term-zzz'));

        // Assert
        $this->assertNotContains($businessUnit->getUuid(), $uuids);
        $this->assertSame([], $uuids, 'A term matching nothing returns an empty collection.');
    }

    public function testEmptySearchTermIsNotApplied(): void
    {
        // Arrange
        [, $businessUnit] = $this->haveBusinessUnitTree();

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())->setSearchTerm(''));

        // Assert
        $this->assertContains($businessUnit->getUuid(), $uuids);
    }

    public function testSortByNameOrdersAscendingAndDescending(): void
    {
        // Arrange
        $token = $this->tester->buildUniqueToken();
        $company = $this->tester->haveCompany();
        $first = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $company->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'Alpha',
        ]);
        $last = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $company->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'Omega',
        ]);

        // Act
        $ascending = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token)
            ->addSort((new SortTransfer())->setField(CompanyBusinessUnitTransfer::NAME)->setIsAscending(true)));
        $descending = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token)
            ->addSort((new SortTransfer())->setField(CompanyBusinessUnitTransfer::NAME)->setIsAscending(false)));

        // Assert
        $this->assertSame([$first->getUuid(), $last->getUuid()], $ascending);
        $this->assertSame([$last->getUuid(), $first->getUuid()], $descending);
    }

    public function testSortByParentNameIsApplied(): void
    {
        // Arrange
        $token = $this->tester->buildUniqueToken();
        $company = $this->tester->haveCompany();
        $underAlpha = $this->haveChildUnder($company->getIdCompany(), $token . 'ParentAlpha', $token . 'ChildA');
        $underOmega = $this->haveChildUnder($company->getIdCompany(), $token . 'ParentOmega', $token . 'ChildB');

        // Act
        $ascending = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Child')
            ->addSort((new SortTransfer())->setField(static::SORT_FIELD_PARENT_NAME)->setIsAscending(true)));

        // Assert
        $this->assertSame([$underAlpha->getUuid(), $underOmega->getUuid()], $ascending);
    }

    public function testSortByCompanyNameIsApplied(): void
    {
        // Arrange
        $token = $this->tester->buildUniqueToken();
        $alpha = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompanyNamed($token . 'CompanyAlpha')->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'UnitOne',
        ]);
        $omega = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompanyNamed($token . 'CompanyOmega')->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'UnitTwo',
        ]);

        // Act
        $ascending = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Unit')
            ->addSort((new SortTransfer())->setField(static::SORT_FIELD_COMPANY_NAME)->setIsAscending(true)));

        // Assert
        $this->assertSame([$alpha->getUuid(), $omega->getUuid()], $ascending);
    }

    public function testSortAppliesEveryFieldItWasGivenInOrder(): void
    {
        // Arrange
        $token = $this->tester->buildUniqueToken();
        $company = $this->tester->haveCompanyNamed($token . 'Company');
        $alphaParent = $this->haveParentNamed($company->getIdCompany(), $token . 'Alpha');
        $middleParent = $this->haveParentNamed($company->getIdCompany(), $token . 'Middle');
        $alphaFirstChild = $this->haveChildOf($alphaParent, $token . 'ChildA');
        $alphaSecondChild = $this->haveChildOf($alphaParent, $token . 'ChildB');
        $middleFirstChild = $this->haveChildOf($middleParent, $token . 'ChildC');
        $middleSecondChild = $this->haveChildOf($middleParent, $token . 'ChildD');
        $omegaChild = $this->haveChildUnder($company->getIdCompany(), $token . 'Omega', $token . 'ChildE');

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Child')
            ->addSort((new SortTransfer())->setField(static::SORT_FIELD_PARENT_NAME)->setIsAscending(true))
            ->addSort((new SortTransfer())->setField(CompanyBusinessUnitTransfer::NAME)->setIsAscending(true)));

        // Assert
        $this->assertSame(
            [
                $alphaFirstChild->getUuid(),
                $alphaSecondChild->getUuid(),
                $middleFirstChild->getUuid(),
                $middleSecondChild->getUuid(),
                $omegaChild->getUuid(),
            ],
            $uuids,
            'The parent name groups the collection, and the business unit name orders each group.',
        );
    }

    protected function haveParentNamed(int $idCompany, string $parentName): CompanyBusinessUnitTransfer
    {
        return $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::NAME => $parentName,
        ]);
    }

    protected function haveChildOf(CompanyBusinessUnitTransfer $parent, string $childName): CompanyBusinessUnitTransfer
    {
        return $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $parent->getFkCompany(),
            CompanyBusinessUnitTransfer::NAME => $childName,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $parent->getIdCompanyBusinessUnit(),
        ]);
    }

    public function testSortOnANonUniqueColumnStillOrdersTiedRowsDeterministically(): void
    {
        // Arrange
        $token = $this->tester->buildUniqueToken();
        $company = $this->tester->haveCompany();
        $first = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $company->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'Same',
        ]);
        $second = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $company->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'Same',
        ]);

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token)
            ->addSort((new SortTransfer())->setField(CompanyBusinessUnitTransfer::NAME)->setIsAscending(true)));

        // Assert
        $this->assertSame([$second->getUuid(), $first->getUuid()], $uuids);
    }

    public function testWithoutASortTheMostRecentlyCreatedBusinessUnitLeads(): void
    {
        // Arrange
        $token = $this->tester->buildUniqueToken();
        $company = $this->tester->haveCompany();
        $older = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $company->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'Older',
        ]);
        $newer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $company->getIdCompany(),
            CompanyBusinessUnitTransfer::NAME => $token . 'Newer',
        ]);

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())->setSearchTerm($token));

        // Assert
        $this->assertSame([$newer->getUuid(), $older->getUuid()], $uuids);
    }

    public function testUnknownSortFieldIsIgnoredRatherThanApplied(): void
    {
        // Arrange
        [$token, $businessUnit] = $this->haveBusinessUnitTree();

        // Act
        $uuids = $this->findUuids((new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setSearchTerm($token . 'Child')
            ->addSort((new SortTransfer())->setField('unknownField')->setIsAscending(true)));

        // Assert
        $this->assertContains($businessUnit->getUuid(), $uuids);
    }

    /**
     * @return array{0: string, 1: \Generated\Shared\Transfer\CompanyBusinessUnitTransfer} Token, then the child unit.
     */
    protected function haveBusinessUnitTree(): array
    {
        $token = $this->tester->buildUniqueToken();
        $company = $this->tester->haveCompanyNamed($token . 'Company');
        $child = $this->haveChildUnder($company->getIdCompany(), $token . 'Parent', $token . 'Child');

        return [$token, $child];
    }

    protected function haveChildUnder(int $idCompany, string $parentName, string $childName): CompanyBusinessUnitTransfer
    {
        $parent = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::NAME => $parentName,
        ]);

        return $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::NAME => $childName,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $parent->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @return array<string>
     */
    protected function findUuids(CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer): array
    {
        $collection = $this->tester->getFacade()->getCompanyBusinessUnitCollection($criteriaFilterTransfer);

        $uuids = [];
        foreach ($collection->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $uuids[] = $companyBusinessUnitTransfer->getUuid();
        }

        return $uuids;
    }
}
