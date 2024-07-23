<?php

declare(strict_types=1);

if (! function_exists('Yard\ConfigExpander\Tests\current_user_can')) {
    function current_user_can($capability)
    {
        global $current_user_can_return_value;

        return $current_user_can_return_value;
    }
}

function setCurrentUserCanMock($returnValue)
{
    global $current_user_can_return_value;
    $current_user_can_return_value = $returnValue;
}
