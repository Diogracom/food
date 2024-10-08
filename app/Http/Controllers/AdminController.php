<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Mail\WebMail;

class AdminController extends Controller
{
    public function adminLogin(){
        return view('admin.login');
    }

    public function adminSubmit(Request $request){
       $data = $request->validate([
            'email' => 'required|exists:admins,email',
            'password' => 'required'
        ]);
 
        //$user = User::where('email', $request->email);

        if(Auth::guard('admin')->attempt($data)){
            return redirect()->route('admin.dashboard')->with('success','Login Successfully');
        }else{
            return redirect()->route('admin.login')->with('error', 'Invalid Credentials');
        }

        // if(!Auth::user() || !Hash::make($request->password, $user->email)){
        //     return redirect()->back();
        // }

    }

    public function adminDashboard(){
        return view('admin.admin_dashboard');
    }

    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Logout Successful');
    }

    public function adminForgetPassword(){
        return view('admin.forget_password');
    }

    public function adminSubmitEmail(Request $request){
         $request->validate([
            'email' => 'required|email|exists:admins'
         ]);

         $check = Admin::where('email', $request->email)->first();

         If(!$check){
            return redirect()->back()->with('error', 'Provided Email Does Not Exist');
         }

         $token = Hash('sha256',time());
         $check->token =  $token;
         $check->update();

         $reset_link = url('admin/reset-password/'.$token.'/'.
         $request->email);
         $subject = "Reset Password";
         $message = "Please Click on the below link to reset your password<br>";
         $message .= "<a href='".$reset_link."'> Click Here </a>";

         \Mail::to($request->email)->send(new WebMail($subject, $message));
        
         return redirect()->back()->with('success', 'Reset Link Sent to your email');
    }

    public function adminGetToken($token, $email){

       $get_data = Admin::where('token', $token)->where('email', $email)->first();

       if(!$get_data){
          return redirect()->route('admin.login')->with('error', 'Invalid Token');
       }

       return view('admin.reset_password', compact('email', 'token'));
    }

    public function adminResetPassword(Request $request){
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        
        $get = Admin::where('email', $request->email)->where('token', $request->token)->first();

        $get->password = Hash::make($request->password);
        $get->token = "";
        $get->update();

        return redirect()->route('admin.login')->with('success', 'Password Reset Successfully');
    }

    public function adminProfile(){
       $id = Auth::guard('admin')->id();
       $profile_data = Admin::where('id', $id)->first();

       return view('admin.admin_profile', compact('profile_data'));

    }

    public function adminProfileUpdate(Request $request){

        $id = Auth::guard('admin')->id();
        $data = Admin::where('id', $id)->first();

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        $oldPhotoPath = $data->photo;

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/admin_photo'),$file_name);
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
        $fullPath = public_path('upload/admin_photo/'.$oldPhotoPath);
        if(file_exists($fullPath)){
            unlink($fullPath);
        }
    }
    // end of image unlink function

    public function adminChangePassword(){
        $id = Auth::guard('admin')->id();
        $profile_data = Admin::find($id)->first();
        return view('admin.admin_change_password', compact('profile_data'));
    }

    public function adminChangePasswordNew(Request $request){
        $request->validate([
            "current_password" => "required",
            "new_password" => "required",
            "confirm_password" => "required|same:new_password"
        ]);

        $admin = Auth::guard('admin')->user();

        if(!Hash::check($admin->password, $request->current_password)){

            $noty = array(
                'message'=> 'Current Password Incorrect',
                'alert-type'=>'error'
            ); 

            return back()->with($noty);            
        }

        Admin::whereId($admin->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $noty = array(
            'message'=> 'Password Changed Successfully',
            'alert-type'=>'success'
        ); 

        return back()->with($noty);       

        
    }

}



