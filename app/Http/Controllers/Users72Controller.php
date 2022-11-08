<?php

namespace App\Http\Controllers;

use App\Models\Detail_data72;
use App\Models\User;
use App\Models\Agama72;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Users72Controller extends Controller
{

    public function profilePage72()
    {
        $user = Auth::user();
        $agama = Agama72::all();

        $usersData = User::find($user->id);
        $detail = $usersData->detail;
        $all_data = array_merge($usersData->toArray(), $detail->toArray());


        return view('profile', ['user' => $all_data, 'agama' => $agama, 'is_preview' => false]);
    }

    public function logoutHandler72()
    {
        $logout = Auth::logout();

        if ($logout) {
            return redirect('/login72');
        } else {
            return back()->with('error', 'Logout gagal');
        }
    }

    public function registerHandler72(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users72',
            'password' => 'required|min:8',
            'repassword' => 'required|same:password',
            'role' => 'required|in:user,admin',
        ]);

        $userData = $request->all();
        $userData["password"] = bcrypt($request->password);
        $userData["is_active"] = $request["role"] == "user" ? 0 : 1;

        $user = new User();
        $user->fill($userData);
        $save = $user->save();

        $detailUser = new Detail_data72();
        $detailUser->id_user = $user->id;
        $detailUser->save();

        if ($save && $detailUser) {
            return redirect('/login72')->with('success', 'Register berhasil');
        } else {
            return back()->with('error', 'Register gagal');
        }
    }

    public function loginHandler72(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $isLogged = Auth::attempt($request->only('email', 'password'));

        if ($isLogged) {
            $user = Auth::user();

            if ($user->role == "user" && $user->is_active == 1) {
                return redirect('/profile72');
            }

            if ($user->role == "admin") {
                return redirect('/dashboard72');
            }

            if ($user->role == "user" && $user->is_active == 0) {
                Auth::logout();
                return back()->with('error', 'Akun anda belum di approve oleh admin');
            }
        }

        return back()->with('error', 'Username atau password salah');
    }

    public function uploadPhotoKTP72()
    {
        $user = Auth::user();
        $detail = User::find($user->id)->detail;

        if ($detail->foto_ktp != "foto_ktp.png") {
            if (file_exists(public_path('gambar/' . $detail->foto_ktp))) {
                unlink(public_path('gambar/' . $detail->foto_ktp));
            }
        }

        $file = request()->file('photoKTP');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('gambar/'), $fileName);

        $detail->foto_ktp = $fileName;
        $savePhoto = $detail->save();

        if ($savePhoto) {
            return back()->with('success', 'Upload foto ktp berhasil');
        } else {
            return back()->with('error', 'Upload foto ktp gagal');
        }
    }

    public function uploadPhotoProfil72()
    {
        $user = Auth::user();
        $detail = User::find($user->id);

        if ($detail->foto != "foto.png") {
            if (file_exists(public_path('gambar/' . $detail->foto))) {
                unlink(public_path('gambar/' . $detail->foto));
            }
        }

        $file = request()->file('photoProfil');

        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('gambar/'), $fileName);

        $detail->foto = $fileName;

        $savePhoto = $detail->save();
        if ($savePhoto) {
            return back()->with('success', 'Upload foto profil berhasil');
        } else {
            return back()->with('error', 'Upload foto profil gagal');
        }
    }

    public function updateProfil72(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users72,email,' . $user->id,
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'id_agama' => 'required',
        ]);

        $userData = User::find($user->id);
        $detail = User::find($user->id)->detail;

        $isAgamaValid = Agama72::find($request->id_agama);

        if (!$isAgamaValid) {
            return back()->with('error', 'Agama tidak valid');
        }

        $userData->name = $request->name;
        $userData->email = $request->email;
        $saveUser = $userData->save();

        $detail->alamat = $request->alamat;
        $detail->tempat_lahir = $request->tempat_lahir;
        $detail->tanggal_lahir = $request->tanggal_lahir;
        $detail->id_agama = $request->id_agama;
        $detail->umur = date_diff(date_create($request->tanggal_lahir), date_create('now'))->y;
        $saveDetail = $detail->save();

        if ($saveUser && $saveDetail) {
            return back()->with('success', 'Update profile berhasil');
        } else {
            return back()->with('error', 'Update profile gagal');
        }
    }

    public function editPasswordPage72()
    {
        return view('editPassword');
    }

    public function updatePassword72(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'old_password' => 'required|min:8',
            'password' => 'required|min:8',
            'repassword' => 'required|same:password',
        ]);

        $userData = User::find($user->id);

        if (!Hash::check($request->old_password, $userData->password)) {
            return back()->with('error', 'Password lama tidak sesuai');
        }

        $userData->password = bcrypt($request->password);
        $saveUser = $userData->save();

        if ($saveUser) {
            return back()->with('success', 'Update password berhasil');
        } else {
            return back()->with('error', 'Update password gagal');
        }
    }
}
