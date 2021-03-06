<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\history;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

use App\alamat;
use App\provinsi;
use App\hotel;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function history(Request $request)
    {
        $history_wait = history::where(['user_id'=>Auth::user()->id, 'confirmed' => 0, 'finished' => 0])->orderBy('id','asc')->get();
        $history_confirm = history::where(['user_id'=>Auth::user()->id, 'confirmed' => 1, 'finished' => 0])->orderBy('id','asc')->get();
        $history_finish = history::where(['user_id'=>Auth::user()->id, 'confirmed' => 1, 'finished' => 1])->orderBy('id','asc')->get();

        return view('user.history')->with(['histories_wait' => $history_wait, 'histories_confirm' => $history_confirm, 'histories_finish' => $history_finish]);
    }

    public function history_detail($id)
    {
        $detail = history::where('id',$id)->get()->all();

        return view('user.history_detail')->with('details',$detail);

    }

    public function rating(Request $request)
    {
        $id = $request->input('historyId');
        $ratingValue = $request->input('ratingValue') * 2;

        $hotel = history::where('id',$id)->update([
            'rating' => $ratingValue
        ]);


        $hotelId = history::select('hotel_id')->where('id',$id)->first();

        $hotel = hotel::select('total_rating','reviewers','rating')->where('id',$hotelId->hotel_id)->first();

        $addRatingTotal = $hotel->total_rating + $ratingValue;
        $addReview = $hotel->reviewers + 1;
        $addRating = $addRatingTotal / $addReview;

        $hotel = hotel::where('id',$hotelId->hotel_id)->update([
            'total_rating' =>  $addRatingTotal,
            'reviewers' => $addReview,
            'rating' => $addRating
        ]);


        return redirect()->back()->with('success', '<div class="text-center"><h5><strong>Penilaian Berhasil Ditambahkan! <br> <div class="text-muted">Ayo, perbanyak pengalaman Hotelinn kamu!</div></strong></h5></div>');
    }

    public function profile (Request $request)
    {

        return view('user.profile');

    }

    public function updateProfile(Request $request){

        $f_name = $request->input('fName');
        $l_name = $request->input('lName');
        $gender = $request->input('gender');
        $birthdate = $request->input('birthdate');

        if($request->hasFile('photo')){
            $photo = $request->file('photo');
            $filename = time() . '.' . $_FILES['photo']['name'];
            $target = 'images/profile';
            $photo->move($target,$filename);
            $updateProfile = User::where('id', Auth::user()->id)->update([
                'photo' => $filename
            ]);
        }

        $updateProfile = User::where('id', Auth::user()->id)->update([
            'fName' => $f_name,
            'lName' => $l_name,
            'gender' => $gender,
            'birthdate' => $birthdate
        ]);

        return redirect(view('home.home'));

    }

    public function changePassword(Request $request)
    {

        $this->validate($request,[
            'current_password' => ['required', new MatchOldPass],
            'new_password' => ['required', 'string', 'min:8'],
            'new_confirm_password' => ['required', 'same:new_password'],
        ]);

        $change = User::find(Auth::user()->id)->update(['password'=> Hash::make($request->new_password)]);

        return redirect()->back()->with('success', '<div class="text-center"><h5><strong>Kata Sandi berhasil diubah!</strong></h5></div>');
    }

}
