<?php
/**
 * Copyright (c) 2024 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see https://www.techdivision.com
 *
 * To obtain a valid license for using this software, please contact us at
 * license@techdivision.com
 */
declare(strict_types=1);

namespace TechDivision\Import\Product\Grouped\Observers;

use Exception;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Product\Grouped\Services\ProductGroupedProcessorInterface;
use TechDivision\Import\Product\Grouped\Utils\ColumnKeys;
use TechDivision\Import\Product\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Utils\MemberNames;
use TechDivision\Import\Product\Grouped\Utils\ProductTypes;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * @copyright Copyright (c) 2024 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link http://www.techdivision.com
 * @author MET <met@techdivision.com>
 */
class CleanUpGroupedProductRelationObserver extends AbstractProductImportObserver
{
    /**
     * @var ProductGroupedProcessorInterface
     */
    protected ProductGroupedProcessorInterface $productGroupedProcessor;

    /**
     * Initialize the observer with the passed product variant processor instance.
     *
     * @param ProductGroupedProcessorInterface $productGroupedProcessor The product variant processor instance
     * @param StateDetectorInterface|null $stateDetector The state detector instance to use
     */
    public function __construct(
        ProductGroupedProcessorInterface $productGroupedProcessor,
        StateDetectorInterface $stateDetector = null
    ) {
        // pass the state detector to the parent constructor
        parent::__construct($stateDetector);

        // initialize the product variant processor instance
        $this->productGroupedProcessor = $productGroupedProcessor;
    }

    /**
     * Return's the product variant processor instance
     *
     * @return ProductGroupedProcessorInterface The product variant processor instance
     */
    protected function getProductGroupedProcessor(): ProductGroupedProcessorInterface
    {
        return $this->productGroupedProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     * @throws Exception
     */
    protected function process()
    {
        // query whether or not we've found a configurable product
        if ($this->getValue(ColumnKeys::PRODUCT_TYPE) !== ProductTypes::GROUPED) {
            return;
        }

        // query whether or not the media gallery has to be cleaned up
        $subject = $this->getSubject();
        $subjectConfiguration = $subject->getConfiguration();

        if ($subjectConfiguration->hasParam(ConfigurationKeys::CLEAN_UP_LINKS)
            && $subjectConfiguration->getParam(ConfigurationKeys::CLEAN_UP_LINKS)) {
            $this->cleanUpGrouped();

            $subject->getSystemLogger()->info(
                $subject->appendExceptionSuffix(
                    sprintf(
                        'Successfully clean up variants for product with SKU "%s"',
                        $this->getValue(ColumnKeys::SKU)
                    )
                )
            );
        }
    }

    /**
     * Search for variants in the artefacts and check for differences in the database. Remove entries in DB that do not
     * exist in artefact.
     *
     * @return void
     * @throws Exception Is thrown if all the variant children und attributes cannot be deleted
     */
    protected function cleanUpGrouped()
    {
        // load the available artefacts from the subject
        $subject = $this->getSubject();
        $artefacts = $subject->getArtefacts();

        // return, if we do NOT have any variant artefacts
        if (!isset($artefacts[ProductGroupedObserver::ARTEFACT_TYPE])) {
            return;
        }

        // load the entity ID of the parent product
        $parentIdForArtefacts = $this->getLastEntityId();

        // return, if we do NOT have any artefacts for the actual entity ID
        if (!isset($artefacts[ProductGroupedObserver::ARTEFACT_TYPE][$parentIdForArtefacts])) {
            return;
        }

        // initialize the array with the SKUs of
        // the child IDs and the attribute codes
        $actualGrouped = [];
        $actualAttributes = [];

        // load the variant artefacts for the actual entity ID
        $allGrouped = $artefacts[ProductGroupedObserver::ARTEFACT_TYPE][$parentIdForArtefacts];

        // iterate over the artefacts with the variant data
        foreach ($allGrouped as $variantData) {
            // add the child SKU to the array
            $actualGrouped[] = $variantData[ColumnKeys::GROUPED_CHILD_SKU];
        }

        // load the row/entity ID of the parent product
        $parentId = $this->getLastPrimaryKey();

        try {
            $this->cleanUpGroupedRelation($parentId, $actualGrouped);
        } catch (Exception $e) {
            // log a warning if debug mode has been enabled
            if ($subject->isDebugMode()) {
                $subject->getSystemLogger()->critical($subject->appendExceptionSuffix($e->getMessage()));
            } else {
                throw $e;
            }
        }
    }

    /**
     * Delete not exists import relations from database.
     *
     * @param int $parentProductId The ID of the parent product
     * @param array $childData The array of variants
     *
     * @return void
     * @throws Exception
     */
    protected function cleanUpGroupedRelation(int $parentProductId, array $childData)
    {
        // we don't want to delete everything
        if (empty($childData)) {
            return;
        }

        // load the SKU of the parent product
        $parentSku = $this->getValue(ColumnKeys::SKU);

        // remove the old variants from the database‚
        $this->getProductGroupedProcessor()->deleteProductRelation(
            [
                MemberNames::PARENT_ID => $parentProductId,
                MemberNames::SKU => $childData,
            ]
        );

        // log a debug message that the image has been removed
        $subject = $this->getSubject();
        $subject->getSystemLogger()->info(
            $subject->appendExceptionSuffix(
                sprintf(
                    'Successfully clean up relations for product with SKU "%s"',
                    $parentSku
                )
            )
        );
    }

    /**
     * Return's the PK to create the product => variant relation.
     *
     * @return int The PK to create the relation with
     */
    protected function getLastPrimaryKey(): int
    {
        return (int)$this->getLastEntityId();
    }
}
