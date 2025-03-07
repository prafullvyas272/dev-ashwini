<?php

namespace App;

use PhpParser\Node\Expr\Cast\Unset_;

enum DeviceType
{
    const UNSET = 1;
    const FREE = 2;
    const LEASING = 3;
    const RESTRICTED = 4;
}
