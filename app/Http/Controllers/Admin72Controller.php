<?php

namespace App\Http\Controllers;

use App\Models\Agama72;
use App\Models\User;
use Illuminate\Http\Request;

class Admin72Controller extends Controller
{
    public function dashboardPage72()
    {
        $user = User::where('role', 'user')->get();
        $agama = Agama72::all();

        return view('dashboard', ['data' => $user, 'agama' => $agama]);
    }

    public function agamaPage72()
    {
        $agama = Agama72::all();

        return view('agama', ['all_agama' => $agama]);
    }

    public function editAgamaPage72(Request $request)
    {

        $id = $request->id;

        $agama = Agama72::find($id);

        if (!$agama) {
            return back()->with('error', 'Agama tidak ditemukan');
        }

        $all_agama = Agama72::all();

        return view('agama', ['all_agama' => $all_agama, 'agama' => $agama]);
    }

    public function detailPage72(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if (!$user) {
            return redirect('/dashboard72')->with('error', 'User tidak ditemukan');
        }

        $agama = Agama72::all();

        $detail = $user->detail;
        $data = array_merge($user->toArray(), $detail->toArray());

        return view('profile', ['user' => $data, 'agama' => $agama, 'is_preview' => true]);
    }


    public function updateUserStatus72(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if (!$user) {
            return redirect('/dashboard72')->with('error', 'User tidak ditemukan');
        }

        $updateStatus = $user->update([
            'is_active' => $user->is_active == 1 ? 0 : 1
        ]);

        if ($updateStatus) {
            return redirect('/dashboard72')->with('success', 'Status berhasil diubah');
        } else {
            return redirect('/dashboard72')->with('error', 'Status gagal diubah');
        }
    }

    public function updateUserAgama72(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if (!$user) {
            return redirect('/dashboard72')->with('error', 'User tidak ditemukan');
        }

        $request->validate([
            'agama' => 'required|exists:agama72,id'
        ]);

        $user->detail->id_agama = $request->agama;
        $updateAgama = $user->detail->save();

        if ($updateAgama) {
            return redirect('/dashboard72')->with('success', 'Agama berhasil diubah');
        } else {
            return redirect('/dashboard72')->with('error', 'Agama gagal diubah');
        }
    }


    public function deleteAgama72(Request $request)
    {
        $id = $request->id;
        $agama = Agama72::find($id);

        if (!$agama) {
            return redirect('/agama72')->with('error', 'Agama tidak ditemukan');
        }

        $deleteAgama = $agama->delete();


        if ($deleteAgama) {
            return redirect('/agama72')->with('success', 'Agama berhasil dihapus');
        } else {
            return redirect('/agama72')->with('error', 'Agama gagal dihapus');
        }
    }

    public function updateAgama72(Request $request)
    {
        $id = $request->id;
        $agama = Agama72::find($id);

        if (!$agama) {
            return redirect('/agama72')->with('error', 'Agama tidak ditemukan');
        }

        $request->validate([
            'nama_agama' => 'required'
        ]);

        $updateAgama = $agama->update([
            'nama_agama' => $request->nama_agama
        ]);

        if ($updateAgama) {
            return redirect('/agama72')->with('success', 'Agama berhasil diubah');
        } else {
            return redirect('/agama72')->with('error', 'Agama gagal diubah');
        }
    }


    public function createAgama72(Request $request)
    {
        $request->validate([
            'nama_agama' => 'required'
        ]);

        $createAgama = Agama72::create([
            'nama_agama' => $request->nama_agama
        ]);

        if ($createAgama) {
            return redirect('/agama72')->with('success', 'Agama berhasil ditambahkan');
        } else {
            return redirect('/agama72')->with('error', 'Agama gagal ditambahkan');
        }
    }
}
