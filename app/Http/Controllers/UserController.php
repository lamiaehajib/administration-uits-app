<?php

namespace App\Http\Controllers;

    

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Notifications\UserCreatedNotification;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash as FacadesHash;

class UserController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

     public function index(Request $request)
     {
         // Récupérer le mot-clé de recherche
         $search = $request->input('search');
     
         // Construire la requête avec recherche
         $query = User::orderBy('id', 'DESC');
         
         if (!empty($search)) {
             $query->where(function ($q) use ($search) {
                 $q->where('name', 'like', '%' . $search . '%'); // Exemple : recherche par nom
                   
             })
             ->orderBy('created_at', 'desc') ;
         }
     
         // Paginer les résultats
         $data = $query->paginate(10);
     
         return view('users.index', compact('data'))
             ->with('i', ($request->input('page', 1) - 1) * 5);
     }

    

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $roles = Role::pluck('name','name')->all();

        return view('users.create',compact('roles'));

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
        'email' => 'required|email|unique:users,email',
    ]);

    $input = $request->all();
    $defaultPassword = '123456'; // Default password
    $input['password'] = Hash::make($defaultPassword);

    $user = User::create($input);

    $user->assignRole('admin'); // Assign default role

    // Prepare email details
    $email = $user->email;
    $password = $defaultPassword;
    $siteUrl = 'http://administration.uits.local:8001/';// Replace with your actual site dashboard URL

    // Send notification
    $user->notify(new UserCreatedNotification($email, $password, $siteUrl));

    return redirect()->route('users.index')
                     ->with('success', 'User created successfully and email notification sent.');
}
     

    

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        $user = User::find($id);

        return view('users.show',compact('user'));

    }

    

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $user = User::find($id);

        $roles = Role::pluck('name','name')->all();

        $userRole = $user->roles->pluck('name','name')->all();

    

        return view('users.edit',compact('user','roles','userRole'));

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

            'email' => 'required|email|unique:users,email,'.$id,

            'password' => 'same:confirm-password',

            'roles' => 'required'

        ]);

    

        $input = $request->all();

        if(!empty($input['password'])){ 

            $input['password'] = FacadesHash::make($input['password']);

        }else{

            $input = Arr::except($input,array('password'));    

        }

    

        $user = User::find($id);

        $user->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

    

        $user->assignRole($request->input('roles'));

    

        return redirect()->route('users.index')

                        ->with('success','User updated successfully');

    }

    

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        User::find($id)->delete();

        return redirect()->route('users.index')
        ->with('success','User deleted successfully');

    }

}