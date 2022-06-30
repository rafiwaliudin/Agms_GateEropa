<?php

namespace App\Http\Controllers;

use App\Library\VoiceRSS;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function comingSoon()
    {
        return view('pages.maintenance.coming-soon');
    }

    public function notFound()
    {
        return view('pages.maintenance.404');
    }

    public function testSpeech(Request $request)
    {
        $tts = new VoiceRSS();
        $voice = $tts->speech([
            'key' => env('VOICE_RSS_API_KEY'),
            'hl' => 'en-us',
            'v' => 'Linda',
            'src' => 'Welcome' . $request->name . "!",
            'r' => '0',
            'c' => 'mp3',
            'f' => '44khz_16bit_stereo',
            'ssml' => 'false',
            'b64' => 'true'
        ]);

        return jsend_success($voice);
    }

    public function testNotification()
    {
        $sound = [
            "mp3" => "assets/audios/bulp.mp3",
            "ogg" => "assets/audios/bulp.ogg",
            "embed" => "assets/audios/bulp.mp3"
        ];

        return jsend_success($sound);
    }
}
