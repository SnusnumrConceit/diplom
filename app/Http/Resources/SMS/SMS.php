<?php

namespace App\Http\Resources\SMS;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SMS extends JsonResource
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
            'id'         =>  $this->id,
            'phone'      =>  $this->phone,
            'message'    =>  $this->message,
            'link'       =>  $this->link,
            'created_at' =>  $this->getDate($this->created_at)
        ];
    }

    public function getDate($date)
    {
        return Carbon::parse($date)->format('d.m.Y H:i:s');
    }
}
