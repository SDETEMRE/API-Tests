<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Service\Repository;

use App\LogAnalyticsService\Domain\Model\FilePath;

interface ServiceLogImporterInterface
{
    public function import(FilePath $filePath): void;

}
