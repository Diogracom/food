<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\client;
use App\Mail\WebMail;

class ClientController extends Controller
{
    public function clientLogin(){
        return view('client.login');
    }

    public function clientSubmit(Request $request){
       $data = $request->validate([
            'email' => 'required|exists:clients,email',
            'password' => 'required'
        ]);
 

        if(Auth::guard('client')->attempt($data)){
            return redirect()->route('client.dashboard')->with('success','Login Successfully');
        }else{
            return redirect()->route('client.login')->with('error', 'Invalid Credentials');
        }

        // if(!Auth::user() || !Hash::make($request->password, $user->email)){
        //     return redirect()->back();
        // }

    }

    public function clientRegister(){
        return view('client.register');
    }

    public function clientRegisterSubmit(Request $request){
         $request->validate([
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required',
            'email'=>'required|email|unique:clients',
            'password'=>'required'
         ]);

         Client::insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'password' =>  Hash::make($request->password)
        ]);
        
        $noty = array(
            'message'=> 'Profile created Successfully',
            'alert-type'=>'success'
        );  
        
        return redirect()->route('client.login')->with($noty);
    }

    public function clientDashboard(){
        return view('client.client_dashboard');
    }

    public function clientLogout(){
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success', 'Logout Successful');
    }

    public function clientForgetPassword(){
        return view('client.forget_password');
    }

    public function clientSubmitEmail(Request $request){
         $request->validate([
            'email' => 'required|email|exists:clients'
         ]);

         $check = Client::where('email', $request->email)->first();

         If(!$check){
            return redirect()->back()->with('error', 'Provided Email Does Not Exist');
         }

         $token = Hash('sha256',time());
         $check->token =  $token;
         $check->update();

         $reset_link = url('client/reset-password/'.$token.'/'.
         $request->email);
         $subject = "Reset Password";
         $message = "Please Click on the below link to reset your password<br>";
         $message .= "<a href='".$reset_link."'> Click Here </a>";

         \Mail::to($request->email)->send(new WebMail($subject, $message));
        
         return redirect()->back()->with('success', 'Reset Link Sent to your email');
    }

    public function clientGetToken($token, $email){

       $get_data = Client::where('token', $token)->where('email', $email)->first();

       if(!$get_data){
          return redirect()->route('client.login')->with('error', 'Invalid Token');
       }

       return view('client.reset_password', compact('email', 'token'));
    }

    public function clientResetPassword(Request $request){
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        
        $get = Client::where('email', $request->email)->where('token', $request->token)->first();

        $get->password = Hash::make($request->password);
        $get->token = "";
        $get->update();

        return redirect()->route('client.login')->with('success', 'Password Reset Successfully');
    }

    public function clientProfile(){
       $id = Auth::guard('client')->id();
       $profile_data = Client::where('id', $id)->first();

       return view('client.client_profile', compact('profile_data'));

    }

    public function clientProfileUpdate(Request $request){

        $id = Auth::guard('client')->id();
        $data = Client::where('id', $id)->first();

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        $oldPhotoPath = $data->photo;

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/client_photo'),$file_name);
            $data->photo = $file_name;

            if($oldPhotoPath && $oldPhotoPath !== $file_name){
                $this->deleteOldPhoto($oldPhotoPath);
            }
        }

        $data->save();  
        
        $noty = array(
            'message'=> 'Profile update Successfully',
            'alert-type'=>'success'
        );  
        
        return redirect()->back()->with($noty);

    }

    public function deleteOldPhoto(string $oldPhotoPath):void{
        $fullPath = public_path('upload/client_photo/'.$oldPhotoPath);
        if(file_exists($fullPath)){
            unlink($fullPath);
        }
    }
    // end of image unlink function

    public function clientChangePassword(){
        $id = Auth::guard('client')->id();
        $profile_data = Client::find($id)->first();
        return view('client.client_change_password', compact('profile_data'));
    }

    public function clientChangePasswordNew(Request $request){
        $request->validate([
            "current_password" => "required",
            "new_password" => "required",
            "confirm_password" => "required|same:new_password"
        ]);

        $client = Auth::guard('client')->user();

        if(!Hash::check($client->password, $request->current_password)){

            $noty = array(
                'message'=> 'Current Password Incorrect',
                'alert-type'=>'error'
            ); 

            return back()->with($noty);            
        }

        Client::whereId($client->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $noty = array(
            'message'=> 'Password Changed Successfully',
            'alert-type'=>'success'
        ); 

        return back()->with($noty); 
        
    }

}



