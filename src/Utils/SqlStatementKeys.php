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

namespace TechDivision\Import\Product\Grouped\Utils;

/**
 * @copyright Copyright (c) 2024 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link http://www.techdivision.com
 * @author MET <met@techdivision.com>
 */
class SqlStatementKeys extends \TechDivision\Import\Product\Utils\SqlStatementKeys
{
    /**
     * The SQL statement to delete a product relation.
     *
     * @var string
     */
    public const DELETE_PRODUCT_RELATION = 'delete.product_relation';
}
