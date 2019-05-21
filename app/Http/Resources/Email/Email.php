<?php

namespace App\Http\Resources\Email;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Email extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  $this->id,
            'smtp_info'     =>  $this->smtp_info,
            'recipient'     =>  $this->recipient,
            'subject'       =>  $this->subject,
            'opens'         =>  $this->opens,
            'clicks'        =>  $this->clicks,
            'created_at'    =>  $this->format($this->created_at),
            'report_class'  =>  $this->report_class
        ];
    }

    public function format($date)
    {
        if ($date < date('Y-m-d')) {
            return Carbon::parse($date)->format('d.m.Y H:i:s');
        } else {
            return Carbon::parse($date)->diffForHumans();
        }
    }
}
