<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(15);

        return view('useraccounts.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        return view('useraccounts.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findorfail($request->input('role'));

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => '',
            ]);
            $user->setPasswordAttribute($request->input('password'));
            $user->assignRole($role);
            $user->saveQuietly();

            // handle image if its present
            if ($request->hasFile('photo')) {
                $fileName = $request->file('photo')->getClientOriginalName();
                $fileExtension = $request->file('photo')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . $user->id . '_' . time() . '.' . $fileExtension;
                $path = $request->file('photo')->storeAs('public/profile_picture', $fileNameToStore);
                $user->photo_url = $fileNameToStore;
                $user->saveQuietly();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('useraccounts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findorfail($id);

        return view('useraccounts.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findorfail($id);
        $roles = Role::all();

        return view('useraccounts.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findorfail($request->input('role'));
            $user = User::findorfail($id);

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => '',
            ]);
            $user->setPasswordAttribute($request->input('password'));
            $user->assignRole($role);
            $user->saveQuietly();

            // handle image if its present
            if ($request->hasFile('photo')) {
                // delete old photo if present
                if($user->photo_url !== null) {
                    $file_path = public_path('storage/profile_picture/' . $user->photo_url);
                    @unlink($file_path);
                }

                // now add new photo
                $fileName = $request->file('photo')->getClientOriginalName();
                $fileExtension = $request->file('photo')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . $user->id . '_' . time() . '.' . $fileExtension;
                $path = $request->file('photo')->storeAs('public/profile_picture', $fileNameToStore);
                $user->photo_url = $fileNameToStore;
                $user->saveQuietly();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('useraccounts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findorfail($id);
        $user->deletedBy()->associate(auth()->user());
        $user->saveQuietly();
        $user->delete();

        return back();
    }
}
