<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 28.05.19
 * Time: 0:25
 */

namespace App\Services;


use App\Http\Resources\Audit\AuditCollection;
use App\Models\Email;
use App\Models\SMS;
use Illuminate\Support\Facades\Log;

class LogService
{
    public function write($request)
    {
        try {
            $data = (object) $request->input();

            switch ($data->type) {
                case 'email': $subject = Email::findOrFail($request->id); break;
                case 'sms': $subject = SMS::findOrFail($request->id); break;
                default: $subject = (object)['type' => 'link']; break;
            }

            if (empty($subject)) {
                throw new \Exception('Invalid subject');
            }

            $log = \App\Models\Log::create([
                'subject' => json_encode([
                    'type' => (! empty($data->type)) ? $data->type : 'link',
                    'id' => $request->id]),
                'ip_address' => $this->get_client_ip(),
                'user_agent' => $request->header('User-Agent')
            ]);
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            Log::warning('LogService error: '.$error->getMessage());
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function store($request)
    {
        try {
            $logs = \App\Models\Log::paginate(25);
            return response()->json([
                'logs' => new AuditCollection($logs)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}