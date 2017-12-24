<?php

namespace App\Listeners;

use App\Events\EncodingChanged;
use App\Services\Conflicts\ConflictScanner;

class RunConflictScan
{
    private $conflictScanner;

    public function __construct() {
        $this->conflictScanner = new ConflictScanner();
    }

    public function handle(EncodingChanged $event) {
        $this->conflictScanner->runConflictScan($event->encoding_id);
    }
}
