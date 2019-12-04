<?php

namespace App\Http\Controllers;

use App\Submission;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function healthcheck(Request $request) {
        $submissions = Submission::all()->count();
        return $submissions;
    }

    // If a request is made to a url that is only for logged in Aerzte,
    // laravel redirectes to /login
    // We actually don´t want to redirect the user to the login of the Aerztebereich
    // but instead show a simple message that does not reveal the url of the aerztebereich
    public function fakelogin(Request $request) {
        return response(['errors' => "unauthorized"], '401');
    }

}
