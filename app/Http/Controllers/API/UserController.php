<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required|string|max:191',
                'email' => 'required|string| max:191|email|unique:users',
                'password' => 'required|string'
            ]
        );

        User::create([
            'email' => $request['email'],
            'name' => $request['name'],
            'password' => Hash::make($request['password'])
        ]);
//        DB::table('users')->insert(
//            ['email' => $request['email'], 'name' => $request['name'], 'password' => Hash::make($request['password']), ]
//        );

        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request,
            [
                'name' => 'required|string|max:191',
                'email' => 'required|string| max:191|email|unique:users,email,'.$user->id, //The user can update his email or leave it as it is . Mind the commas in the statement
                'password' => 'sometimes|string'
            ]
        );

        if($request->photo != $user['photo'])
        {
            $name = time().'.'.explode('/', explode(':', substr($request->photo, 0 ,strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('images/profile/').$name);

            if(file_exists(public_path('images/profile/').$user['photo']))
            {
                @unlink(public_path('images/profile/').$user['photo']);
            }

            DB::table('users')->where('id', $request->id)->update(['photo' => $name]);
//            $request->merge(['photo' => $name]);
        }

        if(!empty($request->password))
        {
            DB::table('users')->where('id', $request->id)->update(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ['msg' => 'success', 'pass' => Hash::make($request['password']) ];
    }

    public function profile()
    {
        return auth('api')->user();
    }

//    public function profile()
//    {
//
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request,
            [
                'name' => 'required|string|max:191',
                'email' => 'required|string| max:191|email|unique:users,email,'.$user->id, //The user can update his email or leave it as it is . Mind the commas in the statement
                'password' => 'sometimes|string'
            ]
        );

        $user->update($request->all());

        return (json_encode(["message" => "success"]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return ['message' => 'Bro, deleted'];
    }
}
