<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 21.05.19
 * Time: 2:52
 */

namespace App\Services;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Response;
use jdavidbakr\MailTracker\Events\LinkClickedEvent;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;
use jdavidbakr\MailTracker\Exceptions\BadUrlLink;
use jdavidbakr\MailTracker\Model\SentEmail;
use jdavidbakr\MailTracker\Model\SentEmailUrlClicked;

class TrackingService
{

    /***
     * Трекинг пикселя
     *
     * @param $hash
     * @return \Illuminate\Http\Response
     */
    public function getPixelTracking($hash)
    {
        $pixel = sprintf('%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%', 71, 73, 70, 56, 57, 97, 1, 0, 1, 0, 128, 255, 0, 192, 192, 192, 0, 0, 0, 33, 249, 4, 1, 0, 0, 0, 0, 44, 0, 0, 0, 0, 1, 0, 1, 0, 0, 2, 2, 68, 1, 0, 59);
        $response = Response::make($pixel, 200);
        $response->header('Content-Type', 'image/gif');
        $response->header('Content-Length', 42);
        $response->header('Cache-Control', 'private, no-cache, no-cache=Set-Cookie, proxy-revalidate');
        $response->header('Expires', 'Wed, 11 Jan 2000 12:59:00 GMT');
        $response->header('Last-Modified', 'Wed, 11 Jan 2006 12:59:00 GMT');
        $response->header('Pragma', 'no-cache');

        $tracker = SentEmail::where('hash', $hash)
            ->first();

        if (! empty($tracker)) {
            $tracker->opens++;
            $tracker->save();
            Event::dispatch(new ViewEmailEvent($tracker));
        }

        return $response;
    }

    /***
     * Трекинг URL
     *
     * @param $url
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws BadUrlLink
     */
    public function getLinkTracking($url, $hash)
    {
        $url = base64_decode(str_replace('$', '/', $url));

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new BadUrlLink('Mail hash:', $hash);
        }

        $tracker = SentEmail::where('hash', $hash)
            ->first();

        if (! empty($tracker)) {
            $tracker->click++;
            $tracker->save();

            $url_clicked = SentEmailUrlClicked::where('url', $url)
                ->where('hash', $hash)
                ->first();

            if (! empty($url_clicked)) {
                $url_clicked->clicks++;
                $url_clicked->save();
            }  else {
                SentEmailUrlClicked::create([
                    'sent_email_id' => $tracker->id,
                    'url' => $url,
                    'hash'=> $tracker->hash
                ]);
            }

            Event::dispatch(new LinkClickedEvent($tracker));
        }
        return redirect($url);
    }
}