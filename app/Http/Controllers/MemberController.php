<?php

namespace App\Http\Controllers;

use App\Camera;
use App\CameraType;
use App\Gender;
use App\Http\Controllers\Request\Api;
use App\Http\Controllers\Request\ApiData;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->api = new Api();
        $this->apiData = new ApiData();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.member.index');
    }

    public function detail(Request $request)
    {
        $member = Member::where('pid', $request->pid)->first();
        return jsend_success($member);
    }

    public function memberList(Request $request)
    {
        $userLogin = Auth::user();
        $members = Member::with('gender')->get();

        $data = array();
        $no = $request->start + 1;
        foreach ($members as $member) {

            if ($userLogin->hasRole('Administrator')) {
                $button = "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $member->id . "' data-route='member/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
            } else {
                $button = "";
            }
            $memberData = array(
                'no' => $no,
                'name' => $member->name,
                'nik' => $member->nik,
//                'gender' => $member->gender->name,
                'address' => $member->address,
                'photo' => "<img src='" . asset('assets/uploads') . '/' . $member->photo1 . "' width='60px' height='50px' >",
                'phone' => $member->phone,
                'email' => $member->email,
                'action' => $button,
            );

            array_push($data, $memberData);
            $no++;
        }

        $results = [
            'draw' => $request->draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ];

        return jsend_success($results, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $genders = Gender::all();

        if ($type === 'manual') {
            return view('pages.admin.member.manual-create', compact('type', 'genders'));
        } elseif ($type === 'idCard') {
            return view('pages.admin.member.id-card-create', compact('type', 'genders'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'idCard') {

            $validator = Validator::make($request->all(), [
                'idCard' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'phone' => 'required|numeric|unique:members',
                'email' => 'string|email|max:255|unique:members',
            ]);

            if ($validator->fails()) {
                flash('Sorry! Your Input is Wrong.')->error();

                return back()->withInput()->withErrors($validator);
            }

            if ($request->idCard != null) {
                $imageName = \Ramsey\Uuid\Uuid::uuid1()->toString() . "." . $request->idCard->getClientOriginalExtension();
                $imagePath = Storage::disk('public')->put('member/' . $request->phone . '/' . $imageName, $request->idCard);
            }

            $ktpRecognition = $this->api->ktpRecognition($request->phone, $imagePath);

            if ($response = $ktpRecognition->success->ktp_detected) {
                $member = new Member();
                $member->pid = $request->phone;
                $member->nik = $response->NIK;
                $member->name = $response->Nama;
                $member->pob = $response->{'Tempat Lahir'};
                $member->dob = Carbon::parse($response->{'Tgl Lahir'});
                if ($response->{'Jenis Kelamin'} == "LAKI-LAKI")
                    $member->gender_id = 1;
                else
                    $member->gender_id = 2;
                $member->address = $response->Alamat;
                $member->phone = $request->phone;
                $member->email = "";
                $member->photo1 = $imagePath;
                $member->save();

                $addMember = $this->api->addMember($member);

                return jsend_success($member, 200);
            }
        } else if ($request->type == 'customerNote') {
            // $validator = Validator::make($request->all(), [
            //     'pid' => 'required|string|max:255',
            // ]);

            // if ($validator->fails()) {
            //     flash('Sorry! Your Input is Wrong.')->error();
            //     return back()->withInput()->withErrors($validator);
            // }

            $pid = $request->pid;
            $name = $request->name;
//            $member = Member::where('pid', $request->pid)->first();

            // Check if already added by phone
            if (!empty($request->phone)) {
                $member = Member::where('pid', $request->phone)->first();
                $pid = $request->phone;
            }

            // Check if name not provided
            if (empty($name)) {
                $name = $pid;
            }

            if (empty($member)) {
                // Create member baru
                if ($request->captureFile == "") {
                    return jsend_error('Sorry! Photo not detected');
                }

                $member = new Member();
                $member->pid = $pid;
                $member->name = $name;
                $member->nik = $pid;
                $member->pob = $request->pob;
                $member->dob = Carbon::parse($request->dob);
                $member->gender_id = $request->gender;
                $member->address = $request->address;
                $member->phone = $request->phone;
                $member->email = $request->email;
                $member->notes = $request->notes;
                $member->photo1 = uploadDecode64($request->captureFile, 'member/' . $member->pid . '/');
                $member->save();

                try {
                    $addMember = $this->api->addMember($member);
                    flash('Yey! Your data is created.')->success();

                    return back();
                } catch (\Exception $exception) {

                    flash('Sorry! Error on process saving.')->error();

                    return back();
                }



            } else {
                // Member sudah ada
                // Hanya update notes
                $member->name = $name;
                $member->notes = $request->notes;
                $member->save();

                flash('Yey! Your data is created.')->success();
                return back();
            }
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|numeric|unique:members',
                /* 'nik' => 'required|numeric|digits:16|unique:members', */
                /* 'pob' => 'required', */
                /* 'dob' => 'required|date', */
                /* 'gender' => 'required', */
                /* 'address' => 'required', */
                /* 'email' => 'required|string|email|max:255|unique:members', */
                'nik' => 'numeric|digits:16|unique:members',
                'dob' => 'date',
                'email' => 'string|email|max:255|unique:members',
            ]);

            if ($validator->fails()) {
                flash('Sorry! Your Input is Wrong.')->error();

                return back()->withInput()->withErrors($validator);
            }

            try {
                $member = new Member();
                $member->pid = $request->phone;
                $member->nik = $request->nik;
                $member->name = $request->name;
                $member->pob = $request->pob;
                $member->dob = Carbon::parse($request->dob);
                $member->gender_id = $request->gender;
                $member->address = $request->address;
                $member->phone = $request->phone;
                $member->photo1 = "../images/users/avatar-1.jpg";
                $member->email = $request->email;
                $member->save();

                $addMember = $this->api->addMemberNoFr($member);

                flash('Yey! Your data is created.')->success();

                return redirect(route('admin.member.capture', $member->id));

            } catch (\Exception $exception) {
                flash('Sorry! Error on process saving.')->error();

                return back();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function capture($id)
    {
        // $camera = Camera::where('id', 1)->first();
        $cameraType = CameraType::where('name', "recognize")->first();
        $camera = Camera::where('camera_type_id', $cameraType->id)->first();

        if (!empty($camera)) {
            $member = Member::where('id', $id)->first();
            return view('pages.admin.member.capture', compact('camera', 'member'));
        } else {
            if (Auth::user()->hasRole('Administrator')) {
                flash('Please create a Recognize camera first')->error();
                $type = "recognize";
                return view('pages.admin.master-data.camera.create', compact('type'));
            }
            else {
                flash('Please tell your admin to create camera Recognize first')->error();
                return redirect(route('admin.dashboard.index'));
            }
        }
    }

    public function updateMember(Request $request)
    {
        if ($request->type == 'data') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|unique:members',
                /* 'nik' => 'required|numeric|min:16|max:16|unique:members,nik,' . $request->nik, */
                /* 'pob' => 'required', */
                /* 'dob' => 'required|date', */
                /* 'gender' => 'required', */
                /* 'address' => 'required', */
                /* 'email' => 'required|string|email|max:255|unique:members', */
                'nik' => 'numeric|min:16|max:16|unique:members',
                'dob' => 'date',
                'email' => 'string|email|max:255|unique:members',
            ]);

            if ($validator->fails()) {
                flash('Sorry! Your Input is Wrong.')->error();
                return back()->withInput()->withErrors($validator);
            }

            try {
                $member = Member::find($request->memberId);
                $member->pid = $request->phone;
                $member->nik = $request->nik;
                $member->name = $request->name;
                $member->pob = $request->pob;
                $member->dob = Carbon::parse($request->dob);
                $member->gender_id = $request->gender;
                $member->address = $request->address;
                $member->phone = $request->phone;
                $member->email = $request->email;
                $member->save();

                flash('Yey! Your data is created.')->success();

                return redirect(route('admin.member.index'));

            } catch (\Exception $exception) {
                flash('Sorry! Error on process saving.')->error();

                return back();
            }
        } elseif ($request->type == 'photo') {

            try {

                if ($request->capture1 == "" && $request->capture2 == "" && $request->capture3 == "" && $request->capture4 == "") {
                    flash('Sorry! Error on process saving.')->error();
                    return back();
                }

                $member = Member::find($request->memberId);
                if ($request->capture1) {
                    $member->photo1 = uploadDecode64($request->capture1, 'member/' . $member->pid . '/');
                }
                if ($request->capture2) {
                    $member->photo2 = uploadDecode64($request->capture2, 'member/' . $member->pid . '/');
                }
                if ($request->capture3) {
                    $member->photo3 = uploadDecode64($request->capture3, 'member/' . $member->pid . '/');
                }
                if ($request->capture4) {
                    $member->photo4 = uploadDecode64($request->capture4, 'member/' . $member->pid . '/');
                }
                $member->save();

                try {
                    if ($request->capture1)
                        $addFace1 = $this->api->addFace($member->pid, $member->photo1);
                    if ($request->capture2)
                        $addFace2 = $this->api->addFace($member->pid, $member->photo2);
                    if ($request->capture3)
                        $addFace3 = $this->api->addFace($member->pid, $member->photo3);
                    if ($request->capture4)
                        $addFace4 = $this->api->addFace($member->pid, $member->photo4);

                    flash('Yey! Your data is created.')->success();

                    return jsend_success('Yey! Your data is created', 200);
                } catch (\Exception $exception) {
                    flash('Sorry! Error on process saving Mata.')->error();

                    return jsend_error('Sorry! Error on process saving Mata.');
                }
            } catch (\Exception $exception) {

                flash('Sorry! Error on process saving.')->error();

                return jsend_error('Sorry! Error on process saving.');
            }


        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {
            $member = Member::find($request->id);
            $member->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($member, 200);

        } catch (\Exception $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
