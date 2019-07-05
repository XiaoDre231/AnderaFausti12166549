<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.add');
    }

    public function save(Request $request)
    {
    //VALIDASI DATA
    $this->validate($request, [
        'name' => 'required|string',
        'phone' => 'required|max:13', //maximum karakter 13 digit
        'password' => 'required|string',
        //unique berarti email ditable admins tidak boleh sama
        'email' => 'required|email|string|' // format yag diterima harus email
    ]);

    try {
        $admins = Admin::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'email' => $request->email
        ]);
        return redirect('/admin')->with(['success' => 'Data telah disimpan']);
    } catch (\Exception $e) {
        return redirect()->back()->with(['error' => $e->getMessage()]);
    }
    }

    public function edit($id)
    {
    $admins = Admin::find($id);
    return view('admin.edit', compact('admins'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|max:13',
            'password' => 'required|string',
            'email' => 'required|email|string|exists:admins,email'
        ]);

        try {
            $admins = Admin::find($id);
            $admins->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => $request->password,
                'email' => $request->email,
                
            ]);
            return redirect('/admin')->with(['success' => 'Data telah diperbaharui']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $admins = Admin::find($id);
        $admins->delete();
        return redirect()->back()->with(['success' => '<strong>' . $admins->name . '</strong> Telah dihapus']);
    }
}

