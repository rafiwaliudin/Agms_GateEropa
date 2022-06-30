<?php

namespace App\Http\Controllers;

use App\BloodType;
use App\Department;
use App\Employee;
use App\Gender;
use App\Position;
use App\Religion;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.employee.index');
    }

    public function employeeList(Request $request)
    {
        $employees = Employee::all();

        $data = array();
        $no = $request->start + 1;
        foreach ($employees as $employee) {
            $employeeData = array(
                'no' => $no,
                'nik' => $employee->nik,
                'name' => $employee->name,
                'pob' => $employee->pob,
                'dob' => Carbon::parse($employee->dob)->format('d-m-Y'),
                'gender' => $employee->gender->name,
                'position' => $employee->position->name,
                'department' => $employee->department->name,
                'role' => $employee->user->getRoleNames(),
                'photo' => "<a data-fancybox='gallery' href='" . asset('assets/uploads/employee/' . $employee->photo) . "'> <img src='" . asset('assets/uploads/employee/' . $employee->photo) . "' width='60px' height='50px' ></a>",
                'action' => "<a href='" . route("admin.employee.edit", $employee->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" .
                    "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $employee->id . "' data-route='employee/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>",
            );

            array_push($data, $employeeData);
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
    public function create()
    {
        $genders = Gender::all();

        $bloodTypes = BloodType::all();

        $religions = Religion::all();

        $positions = Position::all();

        $departments = Department::all();

        return view('pages.admin.master-data.employee.create', compact('genders', 'bloodTypes', 'religions', 'positions', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required',
            'name' => 'required',
            'pob' => 'required',
            'dob' => 'required|date',
            'gender' => 'required',
            'bloodType' => 'required',
            'religion' => 'required',
            'province' => 'required',
            'city' => 'required',
            'position' => 'required',
            'department' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages();
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $employee = new Employee();
            $employee->nik = $request->nik;
            $employee->name = $request->name;
            $employee->pob = $request->pob;
            $employee->dob = Carbon::parse($request->dob);
            $employee->gender_id = $request->gender;
            $employee->blood_type_id = $request->bloodType;
            $employee->religion_id = $request->religion;
            $employee->province = $request->province;
            $employee->city = $request->city;
            $employee->address = $request->address;
            $employee->position_id = $request->position;
            $employee->department_id = $request->department;
            //upload file
            if ($request->photo != null) {
                Storage::disk('public')->put('employee/', $request->photo);
                $employee->photo = $request->photo->hashName();

                try {
                    $addPerson = $this->api->addMember($employee);
                } catch (\Exception $exception) {

                    flash('Sorry! Error on process saving to Mata.')->error();

                    return back();
                }
            }
            //end upload file

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $employee->user_id = $user->id;
            $employee->save();

            $user->assignRole('User');

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.employee.index'));

        } catch (\Exception $exception) {
            dd($exception);
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $genders = Gender::all();

        $bloodTypes = BloodType::all();

        $religions = Religion::all();

        $positions = Position::all();

        $departments = Department::all();

        return view('pages.admin.master-data.employee.edit', compact('employee', 'genders', 'bloodTypes', 'religions', 'positions', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required',
            'name' => 'required',
            'pob' => 'required',
            'dob' => 'required|date',
            'gender' => 'required',
            'bloodType' => 'required',
            'religion' => 'required',
            'province' => 'required',
            'city' => 'required',
            'position' => 'required',
            'department' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user->id,
            'password' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $employee->nik = $request->nik;
            $employee->name = $request->name;
            $employee->pob = $request->pob;
            $employee->dob = Carbon::parse($request->dob);
            $employee->gender_id = $request->gender;
            $employee->blood_type_id = $request->bloodType;
            $employee->religion_id = $request->religion;
            $employee->province = $request->province;
            $employee->city = $request->city;
            $employee->address = $request->address;
            $employee->position_id = $request->position;
            $employee->department_id = $request->department;
            //upload file
            if ($request->photo != null) {
                Storage::disk('public')->put('employee/', $request->photo);
                $employee->photo = $request->photo->hashName();

                try {
                    $addFace = $this->api->addFace($employee->nik, $employee->photo);
                } catch (\Exception $exception) {

                    flash('Sorry! Error on process saving to Mata.')->error();

                    return back();
                }
            }
            //end upload file

            $user = User::find($employee->user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }
            $user->removeRole($user->getRoleNames()->first());
            $user->save();

            $employee->user_id = $user->id;
            $employee->save();

            $user->assignRole('User');

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.employee.index'));

        } catch (\Exception $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
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
            $employee = Employee::find($request->id);
            $user = User::find($employee->user->id);
            $user->delete();
            $employee->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($user, 200);

        } catch (\Exception $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
