<?php

namespace App\Inv\Repositories\Libraries\Storage\Contract;

interface StorageManagerInterface
{
    /**
     * Get the storage engine
     */
    public function engine();
}
