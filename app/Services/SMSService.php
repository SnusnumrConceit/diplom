<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 25.05.19
 * Time: 5:02
 */

namespace App\Services;


use App\Http\Resources\SMS\SMSCollection;
use App\Models\SMS;
use GuzzleHttp\Client;

class SMSService
{
    public function store($request)
    {
        try {
            $sms = SMS::paginate(25);
            return response()->json([
                'sms' => new SMSCollection($sms)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
    # отправка SMS-сообщения
    public function create($request)
    {
        try {
            # проверка на корректность значения
            $request->validated();
            # сохранение СМС-сообщения в БД
            $sms = SMS::create([
                'phone' => $request->phone,
                'message' => $request->message,
                'link' => $request->link
            ]);

            $client = new Client();
            $phone = $request->phone;
            $api_key = env('SMS_API');
            $message = $this->getMessage($request->message, $request->link, $sms->id);

            # формирование запроса к сервису отправки SMS-сообщений
            $url = 'https://smspilot.ru/api.php'
                    .'?send='.urlencode( $message )
                    .'&to='.urlencode( $phone )
                    .'&apikey='.$api_key
                    .'&format=json';

            # отправка запроса с последующим ожиданием ответа
            $response = file_get_contents($url);
            $response = json_decode($response);

            if (! empty($response->error) || ! empty($response->error->description_ru)) {
                throw new \Exception('Не удалось отправить СМС-сообщение. ' . $response->error->description_ru);
            }

            return response()->json([
                'status' => 'success',
                'msg' => 'Сообщение успешно отправлено'
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function info($id)
    {
        try {
            $sms = SMS::findOrFail($id);
            return response()->json([
                'sms' => $sms
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function getPhone($phone)
    {
        return str_replace(['-', '+'], '', $phone);
    }

    public function getMessage($message, $link, $id)
    {
        return $message . ' ' . $link . '?id=' . $id . '&type=sms';
    }
}