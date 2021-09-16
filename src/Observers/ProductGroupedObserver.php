<?php

/**
 * TechDivision\Import\Product\Grouped\Observers\ProductVariantObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Grouped\Observers;

use TechDivision\Import\Product\Link\Utils\ProductTypes;
use TechDivision\Import\Product\Grouped\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * The observer that exports the data that is necessary to create the grouped products to a separate CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */
class ProductGroupedObserver extends AbstractProductImportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'grouped';

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not we've found a configurable product
        if ($this->getValue(ColumnKeys::PRODUCT_TYPE) !== ProductTypes::GROUPED) {
            return;
        }

        // query whether or not, we've associated SKUs
        if ($associatedSkus = $this->getValue(ColumnKeys::ASSOCIATED_SKUS)) {
            // intialize the array for the grouped products
            $artefacts = array();

            // load the parent SKU from the row
            $parentSku = $this->getValue(ColumnKeys::SKU);

            // iterate over all associated SKUs and import them, e. g. the complete value will look like
            // 24-MB01=0.0000,24-MB04=0.0000,24-MB03=0.0000
            foreach ($this->explode($associatedSkus) as $grouped) {
                // explode the SKU and the configurable attribute values, e. g. 24-MB04=0.0000
                list ($childSku, ) = $this->explode($grouped, '=');

                // initialize the product variation itself
                $variation = $this->newArtefact(
                    array(
                        ColumnKeys::GROUPED_PARENT_SKU => $parentSku,
                        ColumnKeys::GROUPED_CHILD_SKU  => $childSku
                    ),
                    array(
                        ColumnKeys::GROUPED_PARENT_SKU => ColumnKeys::SKU,
                        ColumnKeys::GROUPED_CHILD_SKU  => ColumnKeys::ASSOCIATED_SKUS
                    )
                );

                // append the product variation
                $artefacts[] = $variation;
            }

            // append the variations to the subject
            $this->addArtefacts($artefacts);
        }
    }

    /**
     * Create's and return's a new empty artefact entity.
     *
     * @param array $columns             The array with the column data
     * @param array $originalColumnNames The array with a mapping from the old to the new column names
     *
     * @return array The new artefact entity
     */
    protected function newArtefact(array $columns, array $originalColumnNames)
    {
        return $this->getSubject()->newArtefact($columns, $originalColumnNames);
    }

    /**
     * Add the passed product type artefacts to the product with the
     * last entity ID.
     *
     * @param array $artefacts The product type artefacts
     *
     * @return void
     * @uses \TechDivision\Import\Product\Variant\Subjects\BunchSubject::getLastEntityId()
     */
    protected function addArtefacts(array $artefacts)
    {
        $this->getSubject()->addArtefacts(ProductGroupedObserver::ARTEFACT_TYPE, $artefacts);
    }
}
