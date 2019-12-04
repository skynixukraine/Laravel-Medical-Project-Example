<?php

namespace App\Http\Controllers;

use App\Events\UserRegisteredEvent;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    /**
     * Does not return all information.
     * The user doesnt need to see all data.
     *
     * @param Request $request
     * @return mixed
     */
    public function profile (Request $request) {
        $user = $request->user();
        $user->photo_url = $user->getPhotoUrl();

        return $user->only([
            'gender', 'title', 'first_name', 'last_name',
            'street', 'zip', 'city', 'country',
            'email',
            'status',
            'photo_url']);
    }

    // register doctor
    public function store (Request $request) {

        $messages = [
            'gender.required'                   => 'Bitte geben Sie Ihr Geschlecht an.',
            'first_name.required'               => 'Bitte geben Sie Ihren Vornamen an.',
            'last_name.required'                => 'Bitte geben Sie Ihren Nachnamen an.',
            'birthday.required'                 => 'Bitte geben Sie Ihr Geburtsdatum an.',
            'birthday.date'                     => 'Dies ist kein Datum.',
            'birthday.before'                   => 'Bitte prüfen Sie ihre Angabe.',
            'birthday.after'                    => 'Bitte prüfen Sie ihre Angabe.',
            'birthplace.required'               => 'Bitte geben Sie Ihren Geburtsort an.',
            'street.required'                   => 'In welcher Strasse befindet sich Ihre Praxis',
            'zip.required'                      => 'Bitte geben Sie die Postleitzahl an, in der sich Ihre Praxis befindet.',
            'city.required'                     => 'In welcher Stadt befindet sich Ihre Praxis',
            'country.required'                  => 'In welchem Land befindet sich Ihre Praxis',
            'country.in'                        => 'Bitte wählen Sie eines der angegebenen Länder',
            'phone.required'                    => 'Bitte geben Sie Ihre Telefonnummer an.',
            'graduation_year.required'          => 'Bitte geben Sie das Jahr Ihrer Facharztprüfung an.',
            'graduation_year.numeric'           => 'Bitte geben Sie das Jahr 4-stellig ein, z.B. 2010.',
            'graduation_year.min'               => 'Bitte geben Sie das Jahr 4-stellig ein, z.B. 2010.',
            'graduation_year.max'               => 'Bitte geben Sie das Jahr 4-stellig ein, z.B. 2010.',
            'reason_for_application.required'   => 'Bitte sagen Sie uns kurz, wieso Sie sich entschlossen haben, sich bei uns zu registrieren.',
            'email.required'                    => 'Bitte geben Sie Ihre Emailadresse an',
            'email.email'                       => 'Dies ist keine gültige Emailadresse',
            'email.unique'                      => 'Diese Emailadresse ist bereits registriert',
            'password.required'                 => 'Bitte geben Sie ein Passwort an',
            'password.confirmed'                => 'Die Passwörter stimmen nicht überein',
            'password.between'                  => 'Das Passwort muss zwischen 6 und 20 Zeichen lang sein',
            'agb_accepted.accepted'             => 'Bitte bestätigen Sie die AGB'
        ];

        Validator::make($request->all(), [
            'gender'                    => 'required',
            'first_name'                => 'required',
            'last_name'                 => 'required',
            'birthday'                  => 'required|date|before:2000-01-01|after:1920-01-01',
            'birthplace'                => 'required',
            'street'                    => 'required',
            'zip'                       => 'required',
            'city'                      => 'required',
            'country'                   => 'required|in:DE,AT,CH',
            'phone'                     => 'required',
            'graduation_year'           => 'required|numeric|min:1950|max:'.(Carbon::today()->year + 2), // maybe someone wants to register who plans to have his examn in 2 years
            'reason_for_application'    => 'required',
            'email'                     => 'required|email|unique:users,email',
            'password'                  => 'required|confirmed|between:6,20',
            'agb_accepted'              => 'accepted',
        ], $messages)->validate();

        // save user
        $userData = array_merge($request->all(), [
            "password" => Hash::make($request->password),
            "user_id" => User::generateUserID()
        ]);
        $user = User::create($userData);
        $user->refresh();

         event(new UserRegisteredEvent($user));

        return ['status' => $user->status];
    }

    // update profile
    // very similar to store() but
    // - does not include: password, password_confirm, agb
    // - only for logged in users
    public function update (Request $request) {
        $user = Auth::user();
        $messages = [
            'first_name.required'   => 'Bitte geben Sie Ihren Vornamen an.',
            'last_name.required'    => 'Bitte geben Sie Ihren Nachname an.',
            'street.required'       => 'In welcher Strasse befindet sich Ihre Praxis',
            'zip.required'          => 'In welcher Postleitzahl befindet sich Ihre Praxis',
            'city.required'         => 'In welcher Stadt befindet sich Ihre Praxis',
            'country.required'      => 'In welchem Land befindet sich Ihre Praxis',
            'country.in'            => 'Bitte wählen Sie eines der angegebenen Länder',
            'email.required'        => 'Bitte geben Sie Ihre Emailadresse an',
            'email.email'           => 'Dies ist keine gültige Emailadresse',
            'email.unique'          => 'Diese Emailadresse ist bereits registriert',
        ];

        Validator::make($request->all(), [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'street'        => 'required',
            'zip'           => 'required',
            'city'          => 'required',
            'country'       => 'required|in:DE,AT,CH',
            'email'         => ['required', 'email', Rule::unique('users')->ignore($user->id)]
        ], $messages)->validate();

        // udate user
        $user->update($request->all());

        return ['success' => true];
    }

    public function updatePassword(Request $request) {
        $user = Auth::user();
        $messages = [
            'password.required'     => 'Das neue Passwort muss zwischen 6 und 20 Zeichen lang sein',
            'password.confirmed'    => 'Die Passwörter stimmen nicht überein',
            'password.between'      => 'Das Passwort muss zwischen 6 und 20 Zeichen lang sein'
        ];

        Validator::make($request->all(), [
            'password'      => 'required|confirmed|between:6,20'
        ], $messages)->validate();

        // save user
        $user->password = Hash::make($request->password);
        $user->save();

        return ['success' => true];
    }


    public function updatePhoto(Request $request) {

        $user = Auth::user();

        // validation 1: file is part of the request
        if (!$request->has('photo')) {
            return response(['errors' => ['photo' => ['Kein Foto ausgeählt']]], '400');
        }

        try {
            $image = Image::make($request->get('photo'));
        }
        catch (\Exception $e) {
            return response(['errors' => ['photo' => ['Fotodatei kann nicht gelesen werden. Bitte nur .jpg oder .png verwenden.']]], '400');
        }

        // validation: minimum 400px width
        $minWidth = 400;
        if ($image->width() < $minWidth) {
            return response(['errors' => ['photo' => ['Bitte nur Fotos mit einer Mindestauflösung von '.$minWidth.' Pixel Breite verwenden.']]], '400');
        }

        // Resize to 400x400 (we want a square version)
        // convert in any case to jpg
        $img = $image->fit(400, null, function ($constraint) {
            $constraint->upsize();
        })->encode('jpg', 95);

        // in production we upload the photos to S3 (but not to the default bucket "ohn" but to bucket "ohn-public" which is not our default disk !
        // in development and staging we save in /storage/app/aerzte (/public/images/aerzte uses a symlink to /storage/app/aerzte)
        if (App::environment() == "production") $disk = 's3_public';
        else $disk = config('filesystems.default');
        $photoName = User::generatePhotoName();
        Storage::disk($disk)->put('aerzte/' . $photoName . '.jpg', $img->__toString());

        $user->photo = $photoName;
        $user->save();

        // return filename in /uploads folder (without .jpg at the end)
        return response(["success" => true,
                         "photo_url" => $user->getPhotoUrl()]);
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
//        $token->revoke();
        $token->delete();
        return ['status' => 'logged-out'];
    }
}
