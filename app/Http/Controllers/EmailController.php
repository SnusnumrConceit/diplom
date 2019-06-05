<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailFormRequest;
use App\Http\Requests\EmailRequest;
use App\Models\Email;
use App\Services\LogService;
use App\Services\TrackingService;
use Illuminate\Http\Request;
use App\Services\EmailService;

class EmailController extends Controller
{

    public $email, $tracking, $log;

    public function __construct(EmailService $email, TrackingService $tracking, LogService $log)
    {
        $this->email = $email;
        $this->tracking = $tracking;
        $this->log = $log;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->email->store();
    }

    public function getContent(int $id)
    {
        return $this->email->getContent($id);
    }

    public function getUrlDetail(int $id)
    {
        return $this->email->getUrlDetail($id);
    }

    public function getSMTPDetail(int $id)
    {
        return $this->email->getSMTPDetail($id);
    }

    public function getPixelTracking($hash)
    {
        return $this->tracking->getPixelTracking($hash);
    }

    public function getLinkTracking($url, $hash)
    {
        return $this->tracking->getLinkTracking($url, $hash);
    }

    public function sendEmail(EmailFormRequest $request)
    {
        return $this->email->sendEmail($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(EmailRequest $request)
    {
        return $this->email->create($request);
    }

    public function write(Request $request)
    {
        return $this->log->write($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        //
    }
}
