<?php

/**
 * TechDivision\Import\Product\Grouped\Utils\ColumnKeys
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
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Grouped\Utils;

/**
 * Utility class containing the CSV column names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */
class ColumnKeys extends \TechDivision\Import\Product\Link\Utils\ColumnKeys
{

    /**
     * Name for the column 'associated_skus'.
     *
     * @var string
     */
    const ASSOCIATED_SKUS = 'associated_skus';

    /**
     * Name for the column 'grouped_parent_sku'.
     *
     * @var string
     */
    const GROUPED_PARENT_SKU = 'grouped_parent_sku';

    /**
     * Name for the column 'grouped_child_sku'.
     *
     * @var string
     */
    const GROUPED_CHILD_SKU = 'grouped_child_sku';
}
