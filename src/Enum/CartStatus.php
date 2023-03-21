<?php

declare(strict_types=1);

namespace App\Enum;

enum CartStatus: string
{
    // This status is attributed to cart when just opened but not placed yet
    case OPENED = 'opened';

    // This is the status to be used when the client checkouts the cart.
    case PLACED = 'placed';

    // This status will be applied to the cart when it is shipped to the end user.
    case SHIPPED = 'shipped';

    // This status will be used when the cart is canceled
    case CANCELED = 'canceled';

    // This status means that the cart is suspended for some reason
    case SUSPENDED = 'suspended';
}
