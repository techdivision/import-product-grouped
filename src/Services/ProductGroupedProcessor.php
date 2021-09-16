<?php

/**
 * TechDivision\Import\Product\Grouped\Services\ProductGroupedProcessor
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

use TechDivision\Import\Dbal\Actions\ActionInterface;
use TechDivision\Import\Dbal\Connection\ConnectionInterface;
use TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface;

/**
 * The product link processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-grouped
 * @link      http://www.techdivision.com
 */
class ProductGroupedProcessor implements ProductGroupedProcessorInterface
{

    /**
     * A PDO connection initialized with the values from the Doctrine EntityManager.
     *
     * @var \TechDivision\Import\Dbal\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Dbal\Connection\ConnectionInterface                     $connection                The connection to use
     * @param \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface $productRelationRepository The product relation repository to use
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                            $productRelationAction     The product relation action to use
     */
    public function __construct(
        ConnectionInterface $connection,
        ProductRelationRepositoryInterface $productRelationRepository,
        ActionInterface $productRelationAction
    ) {
        $this->setConnection($connection);
        $this->setProductRelationRepository($productRelationRepository);
        $this->setProductRelationAction($productRelationAction);
    }

    /**
     * Set's the passed connection.
     *
     * @param \TechDivision\Import\Dbal\Connection\ConnectionInterface $connection The connection to set
     *
     * @return void
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \TechDivision\Import\Dbal\Connection\ConnectionInterface The connection instance
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Turns off autocommit mode. While autocommit mode is turned off, changes made to the database via the PDO
     * object instance are not committed until you end the transaction by calling ProductProcessor::commit().
     * Calling ProductProcessor::rollBack() will roll back all changes to the database and return the connection
     * to autocommit mode.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.begintransaction.php
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commits a transaction, returning the database connection to autocommit mode until the next call to
     * ProductProcessor::beginTransaction() starts a new transaction.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.commit.php
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rolls back the current transaction, as initiated by ProductProcessor::beginTransaction().
     *
     * If the database was set to autocommit mode, this function will restore autocommit mode after it has
     * rolled back the transaction.
     *
     * Some databases, including MySQL, automatically issue an implicit COMMIT when a database definition
     * language (DDL) statement such as DROP TABLE or CREATE TABLE is issued within a transaction. The implicit
     * COMMIT will prevent you from rolling back any other changes within the transaction boundary.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * Set's the repository to access product relations.
     *
     * @param \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface $productRelationRepository The repository instance
     *
     * @return void
     */
    public function setProductRelationRepository(ProductRelationRepositoryInterface $productRelationRepository)
    {
        $this->productRelationRepository = $productRelationRepository;
    }

    /**
     * Return's the repository to access product relations.
     *
     * @return \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface The repository instance
     */
    public function getProductRelationRepository()
    {
        return $this->productRelationRepository;
    }

    /**
     * Set's the action with the product relation CRUD methods.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $productRelationAction The action with the product relation CRUD methods
     *
     * @return void
     */
    public function setProductRelationAction(ActionInterface $productRelationAction)
    {
        $this->productRelationAction = $productRelationAction;
    }

    /**
     * Return's the action with the product relation CRUD methods.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The action instance
     */
    public function getProductRelationAction()
    {
        return $this->productRelationAction;
    }

    /**
     * Load's the product relation with the passed parent/child ID.
     *
     * @param integer $parentId The entity ID of the product relation's parent product
     * @param integer $childId  The entity ID of the product relation's child product
     *
     * @return array The product relation
     */
    public function loadProductRelation($parentId, $childId)
    {
        return $this->getProductRelationRepository()->findOneByParentIdAndChildId($parentId, $childId);
    }

    /**
     * Persist's the passed product relation data and return's the ID.
     *
     * @param array       $productRelation The product relation data to persist
     * @param string|null $name            The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductRelation($productRelation, $name = null)
    {
        return $this->getProductRelationAction()->persist($productRelation, $name);
    }
}
