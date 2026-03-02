<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE = 'PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE = 'PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_BUSINESS_UNIT_EXPANDER = 'PLUGINS_COMPANY_BUSINESS_UNIT_EXPANDER';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanyBusinessUnitPostSavePlugins($container);
        $container = $this->addCompanyBusinessUnitExpanderPlugins($container);
        $container = $this->addCompanyBusinessUnitPreDeletePlugins($container);

        return $container;
    }

    protected function addCompanyBusinessUnitPostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE, function (Container $container) {
            return $this->getCompanyBusinessUnitPostSavePlugins();
        });

        return $container;
    }

    protected function addCompanyBusinessUnitExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_BUSINESS_UNIT_EXPANDER, function (Container $container) {
            return $this->getCompanyBusinessUnitExpanderPlugins();
        });

        return $container;
    }

    protected function addCompanyBusinessUnitPreDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE, function (Container $container) {
            return $this->getCompanyBusinessUnitPreDeletePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPostSavePluginInterface>
     */
    protected function getCompanyBusinessUnitPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitExpanderPluginInterface>
     */
    protected function getCompanyBusinessUnitExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface>
     */
    protected function getCompanyBusinessUnitPreDeletePlugins(): array
    {
        return [];
    }
}
