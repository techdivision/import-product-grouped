<?php

/**
 * TechDivision\Import\Product\Grouped\Observers\GroupedProductRelationUpdateObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
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
 * @license   https://opensource.org/licenses/MIT
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
