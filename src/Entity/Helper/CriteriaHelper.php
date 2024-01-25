<?php

namespace App\Entity\Helper;

use Doctrine\Common\Collections\Criteria;

class CriteriaHelper {

    private const VALID_OPERATORS = ['eq', 'gt', 'lt', 'neq', 'gte', 'lte'];

    public static function createCriteriaFromQueryParameter(string $queryString, string $entityClass): Criteria {
        $queryArray = [];
        
        parse_str($queryString, $queryArray);
        
        $criteria = Criteria::create();
        $reflection = new \ReflectionClass($entityClass);
    
        foreach ($queryArray as $key => $value) {
            if (strpos($key, '-') !== false) {
                list($field, $operator) = explode('-', $key);
                if (!$reflection->hasProperty($field)) {
                    continue;
                }
                if (!in_array($operator, self::VALID_OPERATORS)) {
                    continue;
                }
                try {
                    $criteria->andWhere(Criteria::expr()->{$operator}($field, $value));
                } catch (\Error) {
                    //simply skip for now
                }
            }
        }
        return $criteria;
    }
}

