<?php

/**
 * TechDivision\Import\Product\Grouped\Services\ProductGroupedProcessorInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Grouped\Services;

use TechDivision\Import\Product\Services\ProductProcessorInterface;
use TechDivision\Import\Product\Services\ProductRelationAwareProcessorInterface;

/**
 * Interface for product grouped processor implementations.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */
interface ProductGroupedProcessorInterface extends ProductProcessorInterface, ProductRelationAwareProcessorInterface
{
}
