<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use App\Events\DoctorActivated;
use App\Models\Doctor;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;

class ActivateDoctor extends Action
{
    public $onlyOnIndex = true;

    public function name()
    {
        return __('Activate');
    }

    public function handle(ActionFields $fields, Collection $doctors)
    {
        foreach ($doctors as $doctor) {
            if ($doctor->getOriginal('status') === Doctor::STATUS_DEACTIVATED ||
                $doctor->getOriginal('status') === Doctor::STATUS_CREATED
            ) {
                $doctor->update(['status' => Doctor::STATUS_ACTIVATED]);
                event(new DoctorActivated($doctor));
            }
        }
    }
}
