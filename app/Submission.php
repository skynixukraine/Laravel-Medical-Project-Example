<?php

namespace App;

use Aws\Sns\Exception\SnsException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Submission extends Model
{
    protected $fillable = [
        'other_symptoms', 'side', 'affected_area', 'treated', 'treatment',
        'description', 'since', 'since_other', 'responsetime', 'gender', 'age', 'city', 'country',
        'email', 'device_id', 'submission_id', 'transaction_id', 'due_at', 'medium', 'closeup_image_id', 'closeup2_image_id', 'overview_image_id',
        'partner_id'
    ];

    protected $hidden = [
//        'id'
    ];

    // needed for nova
    protected $casts = [
        'answered_at' => 'datetime'
    ];

    public static $pricingByResponsetimeOHN = [
        ['responsetime' => 8, 'price' => 24.95, 'displayText' => '24,95 €', 'selected' => true],
        //['responsetime' => 8, 'price' => 24.95, 'displayText' => '24,95 €'],
        //['responsetime' => 8, 'price' => 24.95, 'displayText' => '24,95 €']
    ];

    public static $pricingByResponsetimeITA = [
        ['responsetime' => 24, 'price' => 24.95, 'displayText' => '24,95 €', 'selected' => true],
        ['responsetime' => 12, 'price' => 34.95, 'displayText' => '34,95 €'],
        ['responsetime' => 6, 'price' => 49.95, 'displayText' => '49,95 €']
    ];

    public static $pricingByResponsetimeSNA = [
        ['responsetime' => 24, 'price' => 24.95, 'displayText' => '24,95 €', 'selected' => true],
        ['responsetime' => 12, 'price' => 34.95, 'displayText' => '34,95 €'],
        ['responsetime' => 6, 'price' => 49.95, 'displayText' => '49,95 €']
    ];

    public function validResponsetimes() {
        if     ($this->partner->partner_id == "ita") $pricingTable = Submission::$pricingByResponsetimeITA;
        elseif ($this->partner->partner_id == "sna") $pricingTable = Submission::$pricingByResponsetimeSNA;
        else                                         $pricingTable = Submission::$pricingByResponsetimeOHN;
        return array_column($pricingTable, 'responsetime');
    }

    public function getPrice($responsetime = false) {
        if     ($this->partner->partner_id == "ita") $pricingTable = self::$pricingByResponsetimeITA;
        elseif ($this->partner->partner_id == "sna") $pricingTable = self::$pricingByResponsetimeSNA;
        else                                         $pricingTable = self::$pricingByResponsetimeOHN;

        // responsetime as function argument
        if ($responsetime) {
            $pricingTableKey = array_search($responsetime, array_column($pricingTable, 'responsetime'));
        }
        // responsetime stored in submission
        else  {
            $pricingTableKey = array_search($this->responsetime, array_column($pricingTable, 'responsetime'));
        }

        // No price for this reponsetime
        if ($pricingTableKey === false) return false;

        return $pricingTable[$pricingTableKey]["price"];
    }

    public function stripeCheckoutSecret () {
        return substr($this->id, 0, 2).substr($this->created_at->timestamp, -6);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'submission_id';
    }

    public function assignedTo()
    {
        return $this->belongsTo('App\User', 'assigned_to_user_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function symptoms()
    {
        return $this->belongsToMany('App\Symptom');
    }

    public function partner()
    {
        return $this->belongsTo('App\Partner', 'partner_id');
    }

    public function lastQuestion() {
        return $this->questions()->orderBy('created_at', 'DESC')->first();
    }

    public static function generateSubmissionID()
    {
        do {
            $randomStr = strtolower(str_random(20));
            $existing = self::where('submission_id', $randomStr)->first();
        } while ($existing);
        return $randomStr;
    }

    public static function generateTransactionID()
    {
        do {
            $randomStr = strtolower(str_random(20));
            $existing = self::where('transaction_id', $randomStr)->first();
        } while ($existing);
        return $randomStr;
    }

    public function isOpen() {
        return ($this->status == "open");
    }

    public function questionAllowedForUser($user) {
        if (($this->status == "open") ||
            (($this->status == "assigned" || $this->status == "permanently_assigned") && $this->assigned_to_user_id == $user->id)) {
            $lastQuestion = $this->lastQuestion();
            if (!$lastQuestion || $lastQuestion->answered_at) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * returns the number of left minutes (can be negative if overdue)
     *
     * @return int
     */
    public function minutesLeft() {
        return Carbon::now()->diffInMinutes(new Carbon($this->due_at), false);
    }

    private function createSNSEndpointArn() {
        if (!$this->device_id) return false;
        if (($this->medium != "ios") &&
            ($this->medium != "android")) return false;

        try {
            $partner_id = $this->partner->partner_id;
            if ($this->medium == 'android') $platformApplicationArn = config('aws.'.$partner_id.'.Sns.android_application_arn');
            if ($this->medium == 'ios') $platformApplicationArn = config('aws.'.$partner_id.'.Sns.ios_application_arn');
            $client = App::make('aws')->createClient('sns');
            $result = $client->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $platformApplicationArn,
                'Token' => $this->device_id,
            ));
            return $result['EndpointArn'] ?: false;
        } catch (\Exception $e) {
            Log::error("--- failed to create SNSEndpointArn ---");
            Log::error("medium: " . $this->medium);
            Log::error("ios_application_arn: " . config('aws.Sns.ios_application_arn'));
            Log::error("android_application_arn: " . config('aws.Sns.android_application_arn'));
            Log::error("device_id: " . $this->device_id);
            Log::error($e);
            return false;
        }
    }

    public function sendPushMessage($text = "") {
        $endPointArn = $this->createSNSEndpointArn();
        if (!$endPointArn) return false;

        try {
            $sns = App::make('aws')->createClient('sns');
            $endpointAtt = $sns->getEndpointAttributes(["EndpointArn" => $endPointArn]);
            if ($endpointAtt != 'failed' && $endpointAtt['Attributes']['Enabled'] != 'false') {
                if ($this->medium == "ios") {
                    $payload = json_encode(array('default' => '','APNS' => json_encode(array('aps' => array('alert' => $text,),'badge' => '1','sound' => 'default',))));
                    $sns->publish([
                        'TargetArn' => $endPointArn,
                        'Message' => $payload,
                        'MessageStructure' => 'json'
                    ]);
                }
                elseif ($this->medium == "android")  {
                    $sns->publish([
                        'TargetArn' => $endPointArn,
//                        'Message' => json_encode(["GCM" => ["notification" => ["text" => $text]]]),
                        'Message' => '{ "GCM": "{ \"notification\": { \"text\": \"' . $text . '\", \"sound\" : \"default\" } }" }',
                        'MessageStructure' => 'json'
                    ]);
                }
            }
        } catch (SnsException $e) {
            report($e);
        }
    }

    public function logo() {
        if     ($this->partner->partner_id == "ita") $filename = "intimarzt_logo.png";
        elseif ($this->partner->partner_id == "sna") $filename = "logo.jpg"; // todo: this is not the correct SNA logo!
        else                                         $filename = "logo.jpg";

        return $filename;
    }

    public function tooOld() {

        $now = Carbon::now();
        $created = $this->created_at;
        $diff = $created->diffInDays($now);

        return ($diff>14);
    }
}