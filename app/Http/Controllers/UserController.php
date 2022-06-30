<?php

namespace App\Http\Controllers;

use App\Events\NotificationWasCreated;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.user.index');
    }

    public function userList(Request $request)
    {
        $users = User::all();

        $data = array();
        $no = $request->start + 1;
        foreach ($users as $user) {
            $userData = array(
                'no' => $no,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames(),
                'action' => "<a href='" . route("admin.user.edit", $user->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" .
                    "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $user->id . "' data-route='user/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>",
            );

            array_push($data, $userData);
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
        $roles = Role::all();

        return view('pages.admin.master-data.user.create', compact('roles'));
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
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role;
            $user->save();

            $role = Role::where('id', $request->role)->first();
            $user->assignRole($role->name);

            //            event(new NotificationWasCreated('create', 'user', $user));

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.user.index'));
        } catch (ModelNotFoundException $exception) {
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
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('pages.admin.master-data.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required',
            'password' => 'nullable',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->removeRole($user->getRoleNames()->first());
            $user->role_id = $request->role;
            $user->save();

            $occupant = $user->occupant->first();
            if ($occupant) {
                $phone = substr($request->email, 0, strpos($request->email, '@'));
                $occupant->name = $request->name;
                $occupant->phone = $phone;
                $occupant->save();
            }

            $role = Role::where('id', $request->role)->first();
            $user->assignRole($role->name);

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.user.index'));
        } catch (ModelNotFoundException $exception) {
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

            $user = User::find($request->id);
            $user->occupant->vehicles()->delete();
            $user->occupant()->delete();
            $user->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($user, 200);
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
