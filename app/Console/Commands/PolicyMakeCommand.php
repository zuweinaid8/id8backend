<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\PolicyMakeCommand as Command;

class PolicyMakeCommand extends Command
{
    /**
     * Updates default namespace for models to point to app/Models
     * when creating model policies
     *
     * @param string $stub
     * @param string $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        return parent::replaceModel($stub, 'Models\\' . $model);
    }
}
