<?php

namespace App\Nova\Actions;

use App\Events\DoctorDeactivated;
use App\Models\Doctor;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;

class DeactivateDoctor extends Action
{
    /**
     * Indicates if this action is only available on the resource index view.
     *
     * @var bool
     */
    public $onlyOnIndex = true;

    public function name()
    {
        return __('Deactivate');
    }

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $doctors
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $doctors)
    {
        foreach ($doctors as $doctor) {
            if ($doctor->getOriginal('status') !== Doctor::STATUS_DEACTIVATED) {
                $doctor->update(['status' => Doctor::STATUS_DEACTIVATED]);
                event(new DoctorDeactivated($doctor));
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
