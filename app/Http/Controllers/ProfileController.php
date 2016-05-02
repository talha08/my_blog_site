<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog;
use App\Http\Requests\ProfileRequest;
use App\Profile;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Requests\PhotoRequest;
class ProfileController extends Controller
{


    //blogger profile
    public function profile()
    {
        $profile =Profile::where('user_id',Auth::user()->id)->first();
        $blog= Blog::where('user_id',Auth::User()->id)->orderBy('id','desc')->get();
        return view('auth.profile')
            ->with('title', 'Profile')
            ->with('blog',$blog)
            ->with('user', Auth::user())
            ->with('profile', $profile);


    }






    public function update(ProfileRequest $request)
    {
        try{
            Profile::where('id','=',Auth::user()->id)->update([

                'phone'=>$request->phone,
                'platform'=>$request->platform,
                'position'=>$request->position,
                'organization'=>$request->organization,
                'fb_user'=>$request->fb_user,
                'twitter_user'=>$request->twitter_user,
                'github_user'=>$request->github_user,
                'about_me'=>$request->about_me,

            ]);

            return redirect()->back()->with('title', 'Profile')->with('success','Profile Updated Successfully');

        }catch(Exception $e){
            return redirect()->back()->with('error','Something went wrong, Please try Again.');
        }

    }



    public function photoUpload(PhotoRequest $request){


        if ($request->hasFile('image'))
        {
             $image = $request->file('image');


            $avatar_url = 'uploads/profile/avatar/avatar-'.Auth::user()->id.'.jpg';
            $icon_url = 'uploads/profile/icon/icon-'.Auth::user()->id.'.jpg';

            Image::make($image)->resize(200, 200)->save(public_path($avatar_url));
            Image::make($image)->resize(45, 45)->save(public_path($icon_url));

            $profile = Profile::where('user_id',Auth::user()->id)
                        ->update(array(
                            'img_url' => $avatar_url,
                            'thumb_url' => $icon_url
                        ));

            if($profile){
                return redirect('auth.profile')->with('success','Avatar updated successfully');
            }else{
                return redirect()->back()->with('error','Something went wrong');
            }

        }else{

            return redirect('auth.profile')->with(['error'=>'Image could not be uploaded']);
        }
    }

















}
