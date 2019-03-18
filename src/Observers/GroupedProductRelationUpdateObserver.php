<?php

/**
 * TechDivision\Import\Product\Grouped\Observers\GroupedProductRelationUpdateObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Grouped\Observers;

use TechDivision\Import\Product\Grouped\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductRelationUpdateObserver;

/**
 * Oberserver that provides functionality for the grouped product relation add/update operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */
class GroupedProductRelationUpdateObserver extends AbstractProductRelationUpdateObserver
{

    /**
     * Returns the column name with the parent SKU.
     *
     * @return string The column name with the parent SKU
     */
    protected function getParentSkuColumnName()
    {
        return ColumnKeys::GROUPED_PARENT_SKU;
    }

    /**
     * Returns the column name with the child SKU.
     *
     * @return string The column name with the child SKU
     */
    protected function getChildSkuColumnName()
    {
        return ColumnKeys::GROUPED_CHILD_SKU;
    }
}
