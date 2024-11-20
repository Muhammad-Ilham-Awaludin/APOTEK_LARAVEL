<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        //compact = mangirim data pada view
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|string|unique:users',
            'role' => 'required',
        ]);
        $email = substr($request->email, 0, 3);
        $nama = substr($request->name, 0, 3); 
        $generatedPassword = $email . $nama;


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($generatedPassword),
            'role' => $request->role,
        ]);
        // atau jika seluruh data input akan dimasukkan ke db bisa dengan Medicine::create($request->all());
        return redirect()->back()->with('success', 'Berhasil menambahkan data pengguna!');
    }

    // User berfungsi untuk menampilkan data user dan where ('id) berfungsi untuk 
    public function destroy($id)
    {
        //
        User::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }

    public function edit($id)
    {
        //
        $user = User::find($id);
        //atau $user = user::where('id', $id)->first()

        return view('user.edit', compact('user'));
    }

    public function update(Request $request,User $user, $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|string|unique:users',
            'password' => '',
            'role' => 'required|string|in:admin,user',
        ]);

        $account = User::findOrFail($id);
        $account->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password'=> bcrypt($request->password)
        ]);

        return redirect()->route('user.index')->with('success', 'Berhasil mengubah data!');
    }

    public function login(){
        return view('login');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $request->only(['email', 'password']);
        //Auth::attempt berfungsi untuk memverifikasi apakah email dan password nya sesuai, jika sesuai kan disimpan di Auth
        if (Auth::attempt($user)) {
            return redirect()->route('home.page');
        } else {
            return redirect()->back()->with('failed', 'Proses login gagal, silahkan coba kembali dengan data yang benar!');
        }
    }

    public function logout()
    {
        //Auth::logout berfungsi untuk melogout atau menghapus data
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah logout!');
    }
}
