<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use App\Events\DoctorDeactivated;
use App\Models\Doctor;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;

class DeactivateDoctor extends Action
{
    public $onlyOnIndex = true;

    public function name()
    {
        return __('Deactivate');
    }

    public function handle(ActionFields $fields, Collection $doctors): void
    {
        foreach ($doctors as $doctor) {
            if ($doctor->getOriginal('status') != Doctor::STATUS_DEACTIVATED) {
                $doctor->update(['status' => Doctor::STATUS_DEACTIVATED]);
                event(new DoctorDeactivated($doctor));
            }
        }
    }
}
