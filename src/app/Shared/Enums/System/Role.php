<?php

namespace App\Shared\Enums\System;

enum Role: string
{
    case SuperAdmin = 'Super Admin';

    case VendorManager = 'Vendor Manager';

    case Developer = 'Developer';

    case QA = 'QA';

    case Support = 'Support';

    case ClientAdmin = 'Client Admin';

    case ClientUser = 'Client User';
}