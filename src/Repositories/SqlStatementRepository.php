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

namespace TechDivision\Import\Product\Grouped\Repositories;

use IteratorAggregate;
use TechDivision\Import\Dbal\Utils\SqlCompilerInterface;
use TechDivision\Import\Product\Grouped\Utils\SqlStatementKeys;

/**
 * @copyright Copyright (c) 2024 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link http://www.techdivision.com
 * @author MET <met@techdivision.com>
 */
class SqlStatementRepository extends \TechDivision\Import\Product\Repositories\SqlStatementRepository
{
    /**
     * The SQL statements.
     *
     * @var array
     */
    private array $statements = [
        SqlStatementKeys::DELETE_PRODUCT_RELATION =>
            'DELETE
               FROM ${table:catalog_product_relation}
              WHERE parent_id = :parent_id
                AND child_id
             NOT IN (SELECT `entity_id` FROM ${table:catalog_product_entity} WHERE `sku` IN (:skus))',
    ];

    /**
     * Initializes the SQL statement repository with the primary key and table prefix utility.
     *
     * @param IteratorAggregate<SqlCompilerInterface> $compilers The array with the compiler instances
     */
    public function __construct(IteratorAggregate $compilers)
    {
        // pass primary key + table prefix utility to parent instance
        parent::__construct($compilers);

        // compile the SQL statements
        $this->compile($this->statements);
    }
}
