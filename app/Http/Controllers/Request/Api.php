<?php


namespace App\Http\Controllers\Request;

use App\Camera;
use App\MataToken;
use App\Member;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\json_decode;

class Api
{
    private $sessionToken;

    private $credential = array(
        'email' => 'admin@alfabeta.co.id',
        'password' => 'adminadmin123',
    );

    private $_endpoint = array(
        // POST: Bearer session
        'sessionlogin' => '/api/v1/login',
        //GET List Camera
        'listCamera' => '/api/v1/cameras',
        //POST Start Camera
        'startCamera' => '/api/v1/mata/pnp/start_camera',
        //POST Stop Camera
        'stopCamera' => '/api/v1/mata/pnp/stop_camera',
        //POST Stop All Camera
        'stopAllCamera' => '/api/v1/mata/pnp/stop_all_procs',
        //POST recognize face
        'recognizeFace' => '/api/v1/mata/fr/recognize_face',
        //POST recognize ktp
        'recognizeKtp' => '/api/v1/mata/ocr/recognize_ktp',
        //POST face detection
        'faceDetection' => '/api/v1/mata/fr/check_face_detected',
        //POST face similarity
        'faceSimilarity' => '/api/v1/mata/fr/check_face_similarity',
        //POST add face to recognize
        'addFace' => '/api/v1/person/add_face',
        //POST add face manual to recognize
        'addFaceManualFr' => '/api/v1/person/add_person_manual_fr',
        //POST add face manual to recognize
        'addFaceManualNoFr' => '/api/v1/person/add_person_manual_no_fr',
    );

    public function _session()
    {
        $sessionToken = MataToken::where('status', true)->where('end_token', '>', Carbon::today())->orderBy('start_token', 'desc')->first();

        if ($sessionToken == null) {
            if (empty($sessionToken)) {
                $this->_createToken();
            } else {
                $this->sessionToken = $sessionToken->token;
            }
        } else {
            $this->_createToken();
        }

        return $this->sessionToken;
    }

    private function _createToken()
    {
        $createToken = $this->run('sessionlogin', 'POST', $this->credential);

        $token = new MataToken();
        $token->token = $createToken->auth_token;
        $token->start_token = Carbon::now();
        $token->end_token = Carbon::now()->addDay(7);
        $token->status = true;
        $token->save();

        return $this->sessionToken = $createToken->auth_token;
    }

    private function run($endpointkey, $method = 'POST', $body = array(), $add_headers = null, $type = null)
    {
        if (!$endpointkey) {
            return false;
        }
        if (!isset($this->_endpoint[$endpointkey])) {
            return false;
        }
        //Add header

        $url = env('IP_API_MATA') . ':' . env('MATA_PORT') . $this->_endpoint[$endpointkey];

        $client = new Client();

        if ($type == 'multipart') {
            $headers = array();

            if ($add_headers) {
                $headers = array_merge($headers, $add_headers);
            }

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'multipart' => [
                    $body
                ]
            ]);
        } elseif ($type == 'multi-file') {
            $headers = array();

            if ($add_headers) {
                $headers = array_merge($headers, $add_headers);
            }

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'multipart' => $body

            ]);
        } else {
            $headers = array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            );

            if ($add_headers) {
                $headers = array_merge($headers, $add_headers);
            }

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'form_params' => $body

            ]);
        }

        if ($res->getStatusCode() === 200) {
            $result = json_decode($res->getBody()->getContents());
        } elseif ($res->getStatusCode() === 401) {
            $token = MataToken::where('token', $this->sessionToken)->first();
            $token->status = false;
            $token->save();

            $this->_createToken();

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'json' => $body
            ]);

            $result = json_decode($res->getBody()->getContents());
        } else {
            $result = json_decode($res->getStatusCode());
        }

        return $result;
    }

    private function runNonfree($endpointkey, $method = 'POST', $body = array(), $add_headers = null, $type = null)
    {
        if (!$endpointkey) {
            return false;
        }
        if (!isset($this->_endpoint[$endpointkey])) {
            return false;
        }
        //Add header

        // Run on Mata Cloud
        $url = 'https://mata-api.alfabeta.co.id' . $this->_endpoint[$endpointkey];

        $client = new Client();

        if ($type == 'multipart') {
            /* $headers = array( */
            /*     'Content-Type' => 'multipart/form-data', */
            /* ); */
            $headers = array();

            if ($add_headers) {
                $headers = array_merge($headers, $add_headers);
            }

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'multipart' => [
                    $body
                ]
            ]);
        } elseif ($type == 'multi-file') {
            $headers = array();

            if ($add_headers) {
                $headers = array_merge($headers, $add_headers);
            }

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'multipart' => $body

            ]);
        } else {
            $headers = array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            );

            if ($add_headers) {
                $headers = array_merge($headers, $add_headers);
            }

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'form_params' => $body

            ]);
        }

        if ($res->getStatusCode() === 200) {
            $result = json_decode($res->getBody()->getContents());
        } elseif ($res->getStatusCode() === 401) {
            $token = MataToken::where('token', $this->sessionToken)->first();
            $token->status = false;
            $token->save();

            $this->_createToken();

            $res = $client->request($method, $url, [
                'headers' => $headers,
                'json' => $body
            ]);

            $result = json_decode($res->getBody()->getContents());
        } else {
            $result = json_decode($res->getStatusCode());
        }

        return $result;
    }

    public function startCamera($id)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $camera = Camera::where('id', $id)->first();

        $body = array(
            'name' => $camera->name,
            'input_link' => $camera->input_link,
            'type_proc' => $camera->type_proc,
            'prefix_port' => $camera->prefix_port,
            'tracker_threshold' => $camera->tracker_threshold,
            'inference_threshold' => $camera->inference_threshold,
            'ip_ws_server' => env('IP_STREAMER'),
        );

        if ($camera->cameraType->id == 2) {
            $body['target_objects'] = $camera->target_objects;
            $body['n_counting_lines'] = $camera->n_counting_lines;
            $body['counting_lines'] = $camera->counting_lines;
        } elseif ($camera->cameraType->id == 3) {
            $body['target_objects'] = $camera->target_objects;
            $body['with_mask_intruder'] = $camera->maskFile;
            $body['intruder_time_start'] = Carbon::parse($camera->intruder_time_start)->format('h:i:s');
            $body['intruder_time_end'] = Carbon::parse($camera->intruder_time_end)->format('h:i:s');
        }

        //Add payload
        $api = $this->run('startCamera', 'POST', $body, $headers);

        return $api;
    }

    public function stopCamera($idProc)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'id_proc' => $idProc
        );

        //Add payload
        $api = $this->run('stopCamera', 'POST', $body, $headers);

        return $api;
    }

    public function stopAllCamera()
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array();

        //Add payload
        $api = $this->run('stopAllCamera', 'POST', $body, $headers);

        return $api;
    }

    public function recognizeFace($request)
    {
        if (!$this->_session()) return false;

        if (!$request) {
            return json_encode('request is null');
        } else {
            $nameFile = $request;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        if (file_exists(base_path('public/advision/upload/') . $nameFile)) {
            $body = array(
                'name' => 'img_file',
                'contents' => fopen(base_path('public/advision/upload/') . $nameFile, 'r')
            );
        } else {
            $body = array(
                'name' => 'img_url',
                'contents' => $nameFile
            );
        }

        //Add Payload
        $api = $this->run('recognizeFace', 'POST', $body, $headers, 'multipart');

        return $api;
    }

    public function ktpRecognition($phone, $photo)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        if (file_exists(base_path('public/assets/uploads/') . $photo)) {
            $body = array(
                'name' => 'img_file',
                'filename' => $photo,
                'contents' => fopen(base_path('public/assets/uploads/') . $photo, 'r')
            );
        }

        $api = $this->runNonfree('recognizeKtp', 'POST', $body, $headers, 'multipart');

        return $api;
    }

    public function faceDetection($request)
    {
        if (!$this->_session()) return false;

        if (!$request) {
            return json_encode('request is null');
        } else {
            $nameFile = $request;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        if (file_exists(base_path('public/advision/upload/') . $nameFile)) {
            $body = array(
                'name' => 'img_file',
                'contents' => fopen(base_path('public/advision/upload/') . $nameFile, 'r')
            );
        } else {
            $body = array(
                'name' => 'img_url',
                'contents' => $nameFile
            );
        }

        $api = $this->run('faceDetection', 'POST', $body, $headers, 'multipart');

        return $api;
    }

    public function faceSimilarity($nameFile1, $nameFile2)
    {
        if (!$this->_session()) return false;

        if (!$nameFile1 or !$nameFile2) {
            return json_encode('request is null');
        }

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        if (file_exists(base_path('public/advision/upload/') . $nameFile1) and file_exists(base_path('public/advision/upload/') . $nameFile2)) {
            $body = [
                [
                    'name' => 'img_file1',
                    'contents' => fopen(base_path('public/advision/upload/') . $nameFile1, 'r')
                ],
                [
                    'name' => 'img_file2',
                    'contents' => fopen(base_path('public/advision/upload/') . $nameFile2, 'r')
                ]
            ];
        } else {
            $body = [
                [
                    'name' => 'img_url1',
                    'contents' => $nameFile1
                ],
                [
                    'name' => 'img_url2',
                    'contents' => $nameFile2
                ]
            ];
        }

        $api = $this->run('faceSimilarity', 'POST', $body, $headers, 'multi-file');

        return $api;
    }

    public function listCamera()
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $api = $this->run('listCamera', 'GET', null, $headers);

        return $api;
    }

    public function addMember($dataMember)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];
        $body = array(
            [
                'name' => 'NIK',
                'contents' => $dataMember->nik
            ],
            [
                'name' => 'img_file',
                /* 'contents' => fopen(base_path('public/assets/uploads/member/') . $dataMember->phone . '/' . $dataMember->photo1, 'r') */
                'contents' => fopen(base_path('public/assets/uploads/') . $dataMember->photo1, 'r')
            ],
            [
                'name' => 'nama',
                'contents' => $dataMember->name
            ],
        );

        $api = $this->run('addFaceManualFr', 'POST', $body, $headers, 'multi-file');

        return $api;
    }

    public function addMemberNoFr($dataMember)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];
        $body = array(
                      'NIK' => $dataMember->phone,
                      'nama' => $dataMember->name,
        );

        $api = $this->run('addFaceManualNoFr', 'POST', $body, $headers);

        return $api;
    }

    public function addFace($memberIdCard, $photo)
    {
        if (!$this->_session()) return false;

        $member = Member::where('nik', $memberIdCard)->first();

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];
        $body = array(
            [
                'name' => 'NIK',
                'contents' => $memberIdCard
            ],
            [
                'name' => 'img_file',
                /* 'contents' => fopen(base_path('public/assets/uploads/member/') . $member->phone . '/' . $photo, 'r') */
                'contents' => fopen(base_path('public/assets/uploads/') . $photo, 'r')
            ],
        );

        $api = $this->run('addFace', 'POST', $body, $headers, 'multi-file');

        return $api;
    }

}
