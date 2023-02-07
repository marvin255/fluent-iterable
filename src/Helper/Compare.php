<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * List of operators for Filter::compare.
 */
enum Compare
{
    case EQUAL;
    case NOT_EQUAL;
    case GREATER_THAN;
    case GREATER_THAN_OR_EQUAL;
    case LESS_THEN;
    case LESS_THEN_OR_EQUAL;
}
