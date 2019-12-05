<?php

namespace App\Http\Requests;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SubmissionPostRequest extends FormRequest
{
    protected function getValidatorInstance(){

        // set locale (needed for translated validation messages)
        if (Request::has("partner_id")) {
            $partner = Partner::findByPartnerId(Request("partner_id"));
            if ($partner) App::setLocale($partner->language);
        }

        $validator = parent::getValidatorInstance();

        $validator->sometimes('other_symptoms', 'required', function($input)
        {
            return (isset($input->symptoms) && (in_array(9, $input->symptoms)));
        });

        return $validator;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'closeup_image_id.required'     => __("case-submit.closeup_image_id.required"),
            'closeup2_image_id.required'    => __("case-submit.closeup2_image_id.required"),
            'overview_image_id.required'    => __("case-submit.overview_image_id.required"),
            'agb_accepted.required'         => __("case-submit.agb_accepted.required"),
            'symptoms.required'             => __("case-submit.symptoms.required"),
            'other_symptoms.required'       => __("case-submit.other_symptoms.required"),
            'side.required'                 => __("case-submit.side.required"),
            'affected_area.required'        => __("case-submit.affected_area.required"),
            'since_other.required_if'       => __("case-submit.since_other.required_if"),
            'treated.required'              => __("case-submit.treated.required"),
            'treatment.required_if'         => __("case-submit.treatment.required_if"),

            'since.required'                => __("case-submit.since.required"),
            'since.in'                      => __("case-submit.since.in"),
            'gender.required'               => __("case-submit.gender.required"),
            'gender.in'                     => __("case-submit.gender.in"),
            'age.required'                  => __("case-submit.age.required"),
            'age.between'                   => __("case-submit.age.between"),
            'email.required'                => __("case-submit.email.required"),
            'email.email'                   => __("case-submit.email.email"),
            'exists.partners'               => __("case-submit.exists.partners")
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();

        $rules = [
            'closeup_image_id'  => 'required',
            'closeup2_image_id' => 'required',
            'overview_image_id' => 'required',
            'symptoms'          => 'required', // todo: only allow an array with numbers from 1-9
//          'other_symptoms'    => '???', // todo: must be empty if symptoms does not include 9
            'side'              => 'required|in:"einseitig","beidseitig","nicht sicher"',
            'affected_area'     => 'required',
            'since'             => 'required|in:"weniger als zwei Tage","zwischen 2 bis 6 Tagen","zwischen 1 bis 4 Wochen","länger als 1 Monat","chronisch/permanent","andere Angabe"',
            'since_other'       => 'required_if:since,"andere Angabe"',
            'treated'           => 'required',
            'treatment'         => 'required_if:treated,"1"',
            'agb_accepted'      => 'required',
            'gender'            => 'required|in:m,f',
            'age'               => 'required|numeric|between:10,100',
            'partner_id'        => 'exists:partners,partner_id'
        ];

        $medium = request()->medium;
        $email  = request()->email;
        if ($medium == 'web') $rules['email'] = 'required|email';
        elseif ($email)       $rules['email'] = 'email';

        return $rules;
    }

    public function sanitize()
    {
        $input = $this->all();

        foreach ($input AS $key => $value) {
            if ($key != 'symptoms') { // symptoms is an array
                $input[$key] = filter_var($input[$key], FILTER_SANITIZE_STRING);
            }
        }
        $this->replace($input);
    }

}
