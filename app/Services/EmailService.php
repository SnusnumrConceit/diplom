<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 21.04.19
 * Time: 12:17
 */

namespace App\Services;


use App\Http\Resources\Email\EmailCollection;
use App\Models\Email;
use jdavidbakr\MailTracker\Model\SentEmail;
use jdavidbakr\MailTracker\Model\SentEmailUrlClicked;

class EmailService
{
    public $image;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }

    public function store() {
        try {
            $emails = SentEmail::paginate(25);
            return response()->json([
                'emails' => new EmailCollection($emails)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'msg'    => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    /***
     * Получаю содержимое письма
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContent($id)
    {
        try {
         $email = SentEmail::findOrFail($id);
         return response()->json([
             'content' => $email->content
         ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'msg'    => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    /***
     * Получаю содержимое по кликнутой ссылке
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUrlDetail($id)
    {
        try {
            $url_detail = SentEmailUrlClicked::with('email')
                ->where('sent_email_id', $id)
                ->get();
            return response()->json([
                'url_detail' => $url_detail
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'msg' => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    /***
     * Получаю информацию по SMTP
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSMTPDetail($id)
    {
        try {
            $smtp_detail = SentEmail::with('details')
                ->findOrFail($id);
            return response()->json([
                'smtp_detail' => $smtp_detail
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'msg' => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function sendEmail($request)
    {
        $request->validated();

        $data = [
            'email' => $request->email,
            'subject' => $request->subject,
            'name' => $request->name
        ];

        \Mail::send('test.test', $data, function ($message) use($data) {
            $message->from('from@johndoe.com', 'From Name');
            $message->sender('sender@johndoe.com', 'Sender Name');
            $message->to($data['email'], $data['name']);
            $message->subject($data['subject']);
        });
    }

    public function create($request)
    {
        try {
            $request->validated();
            $email = (new Email([
                'email' => $request->email,
                'image' => $this->image->generate()
            ]))->save();
        } catch (\Exception $error) {
            return response()->json([
                'msg'    => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function update($data)
    {
        try {
            $email = Email::where($data['image'])->first();
            $email->update([
                'ip_address' => $data['ip_address']
            ]);
            $email->save();
        } catch (\Exception $error) {
            return response()->json([
                'msg'    => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            Email::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'msg'    => $error->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}