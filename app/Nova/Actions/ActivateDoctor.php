<?php

namespace App\Nova\Actions;

use App\Events\DoctorActivated;
use App\Events\DoctorApproved;
use App\Models\Doctor;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;

class ActivateDoctor extends Action
{
    /**
     * Indicates if this action is only available on the resource index view.
     *
     * @var bool
     */
    public $onlyOnIndex = true;

    public function name()
    {
        return __('Activate');
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
            $status = $doctor->getOriginal('status');

            if ($status === Doctor::STATUS_ACTIVATED || $status === Doctor::STATUS_CREATED) {
                continue;
            }

            $doctor->update(['status' => Doctor::STATUS_ACTIVATED]);

            event($status === Doctor::STATUS_ACTIVATION_REQUESTED ? new DoctorApproved($doctor) : new DoctorActivated($doctor));
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
