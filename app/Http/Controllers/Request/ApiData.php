<?php


namespace App\Http\Controllers\Request;

use App\Camera;
use App\DataToken;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\json_decode;
use Illuminate\Support\Facades\Log;

class ApiData
{
    private $sessionToken;

    private $credential = array(
        'username' => 'advision',
        'password' => 'adV!s!on2k19',
    );

    private $_endpoint = [
        // POST: Bearer session
        'sessionlogin' => '/api/v1/user/login',
        // POST: Start Specific Data
        'startDataCollection' => '/api/v1/data-collection',
        // DELETE: Stop Specific Data
        'stopDataCollection' => '/api/v1/data-collection',
        // DELETE: Stop All Data
        'stopAllDataCollection' => '/api/v1/data-collection/all',
        // POST: get data counting
        'getDataCounting' => '/api/v1/counter',
        // POST: get data impression
        'getDataImpression' => '/api/v1/counter/impression',
        //POST: get data recognition
        'getDataRecognition' => '/api/v2/fr',
        //POST: get data counting interested
        'getDataCountingInterested' => '/api/v1/fr/sec-range',
        //POST: get data visitor/api/v1/fr/sec-range
        'getDataVisitor' => '/api/v1/counter/total-visitors',
        //POST: get data visitor
        'getDataCustomer' => '/api/v1/fr/total-customers',
        //POST: get data gender and age
        'getDataCustomerGenderAndAge' => '/api/v1/fr/age-gender',
        //POST: get data gender and age split of day
        'getDataCustomerGenderAndAgeOfDays' => '/api/v1/fr/age-gender-days-of-the-week',
        //GET: get data number visitor
        'getDataVisitorNew' => '/api/v1/counter/number-of-visitor',
        //POST: get data number visitor on the month
        'getDataVisitorPerMonth' => '/api/v1/counter/number-of-visitor-per-month',
        'getDataCustomerChart' => '/api/v1/fr/number-of-customer',
        'getDataCustomerReport' => '/api/v1/fr/customers-report',
        'getDataConversionRate' => '/api/v1/counter/traffic-conversion-rate',
        // Scheduler service
        'schedulerService' => '/api/v1/mail-scheduler/sec-range',
        'getHistoryOpenGate' => '/api/v1/agms/history-gate',
        'openGate' => '/api/v1/agms/open-gate'
    ];

    public function _session()
    {
        $sessionToken = DataToken::where('status', true)->where('end_token', '>', Carbon::today())->orderBy('start_token', 'desc')->first();

        if ($sessionToken) {
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

        $token = new DataToken();
        $token->token = $createToken->token;
        $token->start_token = Carbon::now();
        $token->end_token = Carbon::now()->addDay(7);
        $token->status = true;
        $token->save();

        return $this->sessionToken = $createToken->token;
    }

    private function run($endpointkey, $method = 'POST', $body = array(), $add_headers = null)
    {
        if (!$endpointkey) {
            return false;
        }
        if (!isset($this->_endpoint[$endpointkey])) {
            return false;
        }
        //Add header

        $endPointKeyAgms = [
            "getHistoryOpenGate",
            "openGate"
        ];

        if (in_array($endpointkey, $endPointKeyAgms) == true) {
            if ($endpointkey == "openGate") {
                $url = env('IP_AGMS_DATA_COLLECTOR') . ":" . env('AGMS_DATA_PORT') . $this->_endpoint[$endpointkey] . "?occupant_id=" . $body['occupant_id'];
            } else {
                $url = env('IP_AGMS_DATA_COLLECTOR') . ":" . env('AGMS_DATA_PORT') . $this->_endpoint[$endpointkey];
            }
        } else {
            $url = env('IP_API_DATA_COLLECTOR') . ":" . env('DATA_PORT') . $this->_endpoint[$endpointkey];
        }

        Log::emergency($url);
        
        $client = new Client();

        $headers = array(
            'Content-Type' => 'application/json',
        );

        if ($add_headers) {
            $headers = array_merge($headers, $add_headers);
        }

        $res = $client->request($method, $url, [
            'headers' => $headers,
            'json' => $body
        ]);

        if ($res->getStatusCode() === 200) {
            $result = json_decode($res->getBody()->getContents());
        } elseif ($res->getStatusCode() === 401) {
            $token = DataToken::where('token', $this->sessionToken)->first();
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

    public function startDataCollection($id, $interval = 1)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $camera = Camera::where('id', $id)->with('cameraType')->first();

        $body = array(
            'camera_id' => $camera->id,
            'proc_id' => $camera->id_proc,
            'proc_type' => $camera->type_proc,
            'prefix_port' => $camera->prefix_port,
            'interval' => $interval,
            'with_mask_intruder' => $camera->maskFile,
            'intruder_time_start' => Carbon::parse($camera->intruder_time_start)->format('h:i:s'),
            'intruder_time_end' => Carbon::parse($camera->intruder_time_end)->format('h:i:s'),
        );

        if ($camera->cameraType->id !== 1) {
            $body['target_objects'] = explode(",", $camera->target_objects);
        }

        $api = $this->run('startDataCollection', 'POST', $body, $headers);

        return $api;
    }

    public function stopDataCollection($id)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'camera_id' => $id,
        );

        $api = $this->run('stopDataCollection', 'DELETE', $body, $headers);

        return $api;
    }

    public function stopAllDataCollection()
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array();

        $api = $this->run('stopAllDataCollection', 'DELETE', $body, $headers);

        return $api;
    }

    public function getDataCounting($cameraId, $limit = 5, $sort = -1, $label = null, $beginDateTime = null, $endDateTime = null, $paginate)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'camera_id' => $cameraId,
            'limit' => $limit,
            'sort' => $sort,
            'label' => $label,
            'beginDateTime' => $beginDateTime,
            'endDateTime' => $endDateTime,
            'paginate' => $paginate
        );

        $api = $this->run('getDataCounting', 'POST', $body, $headers);

        return $api;
    }

    public function getDataImpression($cameraId)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'camera_id' => $cameraId,
        );

        $api = $this->run('getDataImpression', 'POST', $body, $headers);

        return $api;
    }

    public function getDataRecognition($cameraId, $limit, $pageNo, $startDate, $endDate)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'camera_id' => $cameraId,
            'limit' => $limit,
            'pageNo' => $pageNo,
            'startDate' => $startDate,
            'endDate' => $endDate,
        );

        $api = $this->run('getDataRecognition', 'POST', $body, $headers);

        return $api;
    }

    public function getDataCountingInterested($filterDate)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'date' => $filterDate,
            'range' => 30,
            'count' => true,
        );

        $api = $this->run('getDataCountingInterested', 'POST', $body, $headers);

        return $api;
    }

    public function getDataVisitor($phase, $direction)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "phase" => $phase,
            "direction" => $direction
        );

        $api = $this->run('getDataVisitor', 'POST', $body, $headers);

        return $api;
    }

    public function getDataConversionRate($phase, $direction)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "phase" => $phase,
            "direction" => $direction
        );

        $api = $this->run('getDataConversionRate', 'POST', $body, $headers);

        return $api;
    }

    public function getDataCustomerGenderAge($cameraId, $phase)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "cameraId" => $cameraId,
            "phase" => $phase
        );

        $api = $this->run('getDataGenderAge', 'POST', $body, $headers);

        return $api;
    }

    public function getDataCustomer($phase)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "phase" => $phase,
        );

        $api = $this->run('getDataCustomer', 'POST', $body, $headers);

        return $api;
    }

    public function getDataCustomerGenderAndAgeOfDays($cameraId)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "cameraId" => $cameraId
        );

        $api = $this->run('getDataCustomerGenderAndAgeOfDays', 'POST', $body, $headers);

        return $api;
    }

    public function getDataVisitorPerMonth($direction)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "direction" => $direction
        );

        $api = $this->run('getDataVisitorNew', 'POST', $body, $headers);

        return $api;
    }

    public function getDataCustomerChart()
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array();

        $api = $this->run('getDataCustomerChart', 'POST', $body, $headers);

        return $api;
    }

    public function getDataCustomerReport($cameraId, $data)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            "cameraId" => $cameraId,
            "phase" => $data->phase,
            "beginDateTime" => $data->beginDateTime,
            "endDateTime" => $data->endDateTime
        );

        // dd($headers, $body);

        $api = $this->run('getDataCustomerReport', 'POST', $body, $headers);

        return $api;
    }

    public function startScheduler($scheduler)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $emailsCc = [];

        $scheduler->email_cc_1 !== null ? array_push($emailsCc, $scheduler->email_cc_1) : $emailsCc;
        $scheduler->email_cc_2 !== null ? array_push($emailsCc, $scheduler->email_cc_2) : $emailsCc;
        $scheduler->email_cc_3 !== null ? array_push($emailsCc, $scheduler->email_cc_3) : $emailsCc;
        $scheduler->email_cc_4 !== null ? array_push($emailsCc, $scheduler->email_cc_4) : $emailsCc;
        $scheduler->email_cc_5 !== null ? array_push($emailsCc, $scheduler->email_cc_5) : $emailsCc;

        $body = array(
            'to' => [$scheduler->email_to],
            'cc' => $emailsCc,
            'schedule' => $scheduler->schedule_time,
            'range' => $scheduler->range,
        );

        //Add payload
        $api = $this->run('schedulerService', 'POST', $body, $headers);

        return $api;
    }

    public function stopScheduler($scheduler)
    {
        if (!$this->_session()) return false;

        $headers = [
            'Authorization' => 'Bearer ' . $this->_session(),
        ];

        $body = array(
            'id' => $scheduler->scheduler_id,
        );

        //Add payload
        $api = $this->run('schedulerService', 'POST', $body, $headers);

        return $api;
    }

    public function getHistoryOpenGate($limit, $pageNo, $startDate, $endDate)
    {
        if (!$this->_session()) return false;

        $headers = [];

        $body = array(
            'limit' => $limit,
            'pageNo' => $pageNo,
            'sort' => 'desc',
            'startDate' => $startDate,
            'endDate' => $endDate,
        );

        $api = $this->run('getHistoryOpenGate', 'POST', $body, $headers);

        return $api;
    }

    public function openGate($occupantId)
    {
        if (!$this->_session()) return false;

        $headers = [];

        $body = array(
            'occupant_id' => $occupantId,
        );

        $api = $this->run('openGate', 'GET', $body, $headers);

        return $api;
    }
}
