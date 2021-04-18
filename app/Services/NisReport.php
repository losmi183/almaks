<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Support\Facades\Http;

class NisReport {

    // Login VARS
    private $loginUrl = 'https://cards.nis.rs/web-portal-api-web/rest/security/logon';
    private $user = 'almakstest';
    private $password = 'Test1234';
    private $languageId = 'C33DC90A-ABB5-488F-823C-0B159C2EC9EF';

    // Get Report Vars
    private $getReportUrl = 'https://cards.nis.rs/web-portal-api-web/rest/members/reports/fetchClientTransactionsReport';
    // private $token;


    /**
     * Login method make post request with user and password and return $token
     */
    public function login()
    {
        $response = Http::post($this->loginUrl, [
            'user' => $this->user,
            'password' => $this->password,
            'languageId' => $this->languageId
        ]);
        $decoded_response = json_decode($response);
        return $decoded_response->token;
    }

    /**
     * getReport method fetching reports for last day and return array of objects
     */
    public function getReport($token)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            // 'authorization' => 'Bearer 7af3887f-830b-4079-9c56-169abc7949ce'
        ])
        ->withToken($token)
        ->post($this->getReportUrl, [
            'cardGroupDetails' => [],
            'cardInfoBasic' => [],
            'cardTypeId' => [],
            'clientBasicInfo' => [],
            'companyCodeId' => "",
            'dateFrom' => $this->yesterdayTime(),
            'dateTo' => $this->currentTime(),
            'fuellingInHomeCountry' => "",
            'languageId' => "C33DC90A-ABB5-488F-823C-0B159C2EC9EF",
            'outOfTankAllowed' => "",
            'pageNo' => 1,
            'perPage' => 1000,
            'productCategory' => [],
            'sortBy' => "",
            'sortDir' => ""
        ]);
    
        $decoded_response = json_decode($response);

        return $decoded_response->data->listOfObjects;
    }

    private function currentTime()
    {
        return Carbon::now()->timestamp . '000';
    }

    // 17 days ago for testing
    private function yesterdayTime()
    {
        $current = Carbon::now()->timestamp;

        $yesterday = $current - (24 * 60 * 60 * 17);

        return $yesterday . '000';
    }

    /**
     * Method save data to DB
     */
    public function saveReports($allReports)
    {
        foreach($allReports as $report)
        {
            Report::create([
                'kartica' => $report->cardNumber,
                'kolicina' => $report->amount,
                'vrsta' => $report->productName,
                'cena' => $report->total,
            ]);
        }
    }


}