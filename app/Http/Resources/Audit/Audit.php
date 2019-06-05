<?php

namespace App\Http\Resources\Audit;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Audit extends JsonResource
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
            'id' => $this->id,
            'subject' => $this->getSubject($this->subject),
            'event' => $this->getEvent($this->subject),
            'ip' => $this->ip_address,
            'browser' => $this->getClient($this->user_agent),
            'created_at' => $this->getDate($this->created_at)
        ];
    }

    public function getDate($date)
    {
        return Carbon::parse($date)->format('d.m.Y H:i:s');
    }

    public function getClient($user_agent)
    {
        return $user_agent;
    }

    public function getEvent($subject)
    {
        $subject = json_decode($subject);

        switch($subject->type) {
            case 'email': return 'Электронное письмо'; break;
            case 'sms': return 'СМС-сообщение'; break;
            default: return 'По ссылке';
        }
    }

    public function getSubject($subject)
    {
        $subject = json_decode($subject);

        switch ($subject->type) {
            case 'emails':  $link = (! empty ($subject->link)) ? $subject->link : '';
                            $type = (! empty($subject->type)) ? $subject->type : ''; break;
            case 'sms':  $link = (! empty ($subject->link)) ? $subject->link : '';
                         $type = (! empty($subject->type)) ? $subject->type : ''; break;
            default: $link = ''; $type = ''; break;
        }
        return (object) [
            'link' => $link,
            'type' => $type
        ];
    }
}
