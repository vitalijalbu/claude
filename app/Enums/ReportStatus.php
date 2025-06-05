<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
