<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct(
        protected User $user
    )
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    public function edit()
    {
        return view("cafeapp.profile", [
            "photo" => (!empty($this->user->photo) ? $this->user->photo : asset("/assets/images/avatar.jpg")),
            "user" => $this->user
        ]);
    }


    public function profile(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "password" => ["nullable", "string", "confirmed", Password::min(8)],
            "document" => ["size:15"],
            "datebirth" => ["after:1990-01-01", "before:2008-01-01"]
        ]);       


        if($validate->fails())
        {
            return Response()->json([
                "message" => $this->message->warning($validate->errors()->first())->render() 
            ]);
        }

        $validateData = $validate->getData();
        
        list($d, $m, $y) = explode("/", $validateData["datebirth"]);

        $this->user->first_name = $validateData["first_name"];
        $this->user->last_name = $validateData["last_name"];
        $this->user->gender = $validateData["gender"];
        $this->user->document = preg_replace("/[^A-Za-z0-9]/", "", $validateData["document"]);
        $this->user->datebirth = "{$y}-{$m}-{$d}";

        if ($request->hasFile("photo")) {
            $name = str_replace("storage/", "", $this->user->photo);
            Storage::disk('public')->delete($name);
            $file = $request->file("photo");
            $name = $file->hashName();
            $file->storeAs('images/',$name, 'public');
            $this->user->photo =  "/storage/images/".$name;
        }


        try{
            $this->user->save();

        }catch(Exception $e){
            return Response()->json([
                "message" => $this->message->warning($e->getMessage())->render()
            ]);
        }


        return Response()->json([
            "message" => $this->message->success("Pronto {$this->user->first_name}. Seus dados foram actualizado 
            com sucesso!")->render()
        ]);

    }
}
