<?php

namespace App\Http\Controllers;

use App\Http\Requests\SMSRequest;
use App\Models\SMS;
use App\Services\SMSService;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    public $sms;

    public function __construct(SMSService $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SMSRequest $request)
    {
        return $this->sms->create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->sms->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function info(Request $request, int $id)
    {
        return $this->sms->info($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function edit(SMS $sMS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SMS $sMS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function destroy(SMS $sMS)
    {
        //
    }
}
