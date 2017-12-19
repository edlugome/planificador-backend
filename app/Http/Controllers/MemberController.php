<?php

namespace App\Http\Controllers;
use App\Member;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    
    function add(Request $request)
    {

        if($request->isJson())
        {
            if(Member::where('username', $request['username'])->first()!=null || Member::where('email', $request['email'])->first()!=null || Member::where('phonenumber', $request['phonenumber'])->first()!=null )
            {
                return response()->json(['Error','El nombre de usuario o correo ya se encuentran registrados.'], 209);
            }
            else
            {
                $member = Member::create([
                'name' => $request['name'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'phonenumber' => $request['phonenumber'],
                'username' => $request['username'],
                'password'=> Hash::make($request['password']),
                'api_token' => str_random(60),
                'remember_token' => str_random(60)
            ]); 

            return response()->json($member, 201);   
                
            }
        }
        else
        {
            return response()->json(['error'=>'No Autorizado'], 401, []);
        }

    }

    function getToken(Request $request)
    {
        if($request->isJson())
        {
            $data=$request->json()->all();
            $user=Member::where('username', $data['username'])->first();
            try
            {
                if($user && Hash::check($data['password'], $user->password))
                {
                    return response()->json($user, 200);
                }
                else
                {
                    return response()->json(['error'=>'Usuario o contraseña incorrecto, por favor intente de nuevo.'], 210);
                }
            }
            catch(ModelNotFoundException $e)
            {
                return response()->json(['error'=>'Usuario o contraseña incorrecto'], 210);
            }

        }
        else
        {
            return response()->json(['error'=>'No autorizado'], 401);
        }
    }

    function usuarios()
    {
        $miembros = Member::all();
        return response()->json($miembros, 200, [], JSON_PRETTY_PRINT);
    }

    function hash($h)
    {
        return Hash::check($h);
    }

    function users($username)
    {
        $members=Member::where('username', 'LIKE', '%'.$username.'%')->get();
        return response()->json($members, 201, []);
    }

}
