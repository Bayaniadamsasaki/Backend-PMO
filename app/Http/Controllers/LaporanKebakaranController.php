<?php

namespace App\Http\Controllers;

use App\Models\LaporanKebakaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LaporanKebakaranController extends Controller
{
    // Menampilkan semua laporan kebakaran
    public function index()
    {
        $laporans = LaporanKebakaran::all();

        // Format foto URL
        foreach ($laporans as $laporan) {
            $laporan->foto = url('storage/laporan_kebakaran/' . $laporan->foto);
        }

        return response()->json($laporans);
    }

    // Menyimpan laporan kebakaran baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jenis'    => 'required|string',
            'nama'     => 'required|string',
            'telepon'  => 'required|string',
            'lokasi'   => 'required|string',
            'tanggal'  => 'required|string', 
            'isi'      => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('public/laporan_kebakaran', $image->hashName());
        }

        $laporan = LaporanKebakaran::create([
            'foto'     => $image->hashName(),
            'jenis'    => $request->jenis,
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'lokasi'   => $request->lokasi,
            'tanggal'  => $request->tanggal, 
            'isi'      => $request->isi,
        ]);

        $laporan->foto = url('storage/laporan_kebakaran/' . $laporan->foto);

        return response()->json(['success' => "Laporan kebakaran berhasil ditambahkan!"], 201);
    }


    // Menampilkan laporan kebakaran tertentu
    public function show($id)
    {
        $laporan = LaporanKebakaran::findOrFail($id);
        return response()->json($laporan);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'foto'     => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jenis'    => 'sometimes|required|string',
            'nama'     => 'sometimes|required|string',
            'telepon'  => 'sometimes|required|string',
            'lokasi'   => 'sometimes|required|string',
            'tanggal'  => 'sometimes|required|string', 
            'isi'      => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $laporan = LaporanKebakaran::findOrFail($id);

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('public/laporan_kebakaran', $image->hashName());

            if ($laporan->foto) {
                Storage::delete('public/laporan_kebakaran/' . $laporan->foto);
            }
            $laporan->foto = $image->hashName();
        }

        $laporan->update($request->except('foto') + ['foto' => $laporan->foto]);

        $laporan->foto = url('storage/laporan_kebakaran/' . $laporan->foto);

        return response()->json($laporan);
    }


    // Menghapus laporan kebakaran 
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $laporan = LaporanKebakaran::findOrFail($id);
        $laporan->delete();
        return response()->json(['success' => 'Laporan kebakaran berhasil dihapus'], 200);
    }
}
