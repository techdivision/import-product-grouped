<?php
/**
 * Copyright (c) 2024 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see https://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */
declare(strict_types=1);

namespace TechDivision\Import\Product\Grouped\Observers;

use Exception;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Product\Grouped\Services\ProductGroupedProcessorInterface;
use TechDivision\Import\Product\Grouped\Utils\ColumnKeys;
use TechDivision\Import\Product\Grouped\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * @copyright Copyright (c) 2024 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link      https://www.techdivision.com/
 * @author    MET <met@techdivision.com>
 */
class CleanUpGroupedProductRelationsObserver extends AbstractProductImportObserver
{
    /** @var ProductGroupedProcessorInterface */
    protected $productGroupedProcessor;

    /**
     * @param ProductGroupedProcessorInterface $productGroupedProcessor
     * @param StateDetectorInterface|null $stateDetector
     */
    public function __construct(
        ProductGroupedProcessorInterface $productGroupedProcessor,
        StateDetectorInterface $stateDetector = null
    ) {
        parent::__construct($stateDetector);
        $this->productGroupedProcessor = $productGroupedProcessor;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function process()
    {
        if ($this->getValue(ColumnKeys::PRODUCT_TYPE) !== Grouped::TYPE_CODE) {
            return;
        }

        if ($this->getSubject()->getConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_GROUPED) &&
            $this->getSubject()->getConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_GROUPED)
        ) {
            $this->cleanUpGrouped();

            $this->getSubject()
                ->getSystemLogger()
                ->debug(
                    $this->getSubject()->appendExceptionSuffix(
                        sprintf(
                            'Successfully clean up variants for product with SKU "%s"',
                            $this->getValue(ColumnKeys::SKU)
                        )
                    )
                );
        }
    }

    /**
     * Search for grouped products in the artefacts and check for differences in
     * the database. Remove entries in DB that not exist in artefact.
     *
     * @return void
     * @throws Exception Is thrown, if either the grouped children or attributes can not be deleted
     */
    protected function cleanUpGrouped()
    {
        // TODO herausfinden wie
    }

    /**
     * Return's the PK to create the product => variant relation.
     *
     * @return int The PK to create the relation with
     */
    protected function getLastPrimaryKey()
    {
        return $this->getLastEntityId();
    }

    /**
     * @param int $parentProductId The ID of the parent product
     * @param array $childData The array of variants
     *
     * @return void
     * @throws Exception
     */
    protected function cleanUpGroupedChildren($parentProductId, array $childData)
    {

        // we don't want to delete everything
        if (empty($childData)) {
            return;
        }

        // load the SKU of the parent product
        $parentSku = $this->getValue(ColumnKeys::SKU);

        // TODO remove the old links
    }

    /**
     * Return's the product variant processor instance.
     *
     * @return ProductGroupedProcessorInterface
     */
    protected function getProductGroupedProcessor()
    {
        return $this->productGroupedProcessor;
    }
}
