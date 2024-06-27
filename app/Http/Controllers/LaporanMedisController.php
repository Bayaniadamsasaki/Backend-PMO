<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanMedis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaporanMedisController extends Controller
{
    //Menampilkan semua laporan medis
    public function index()
    {
        $laporans = LaporanMedis::all();

        // Format foto URL
        foreach ($laporans as $laporan) {
            $laporan->foto = url('storage/laporan_medis/' . $laporan->foto);
        }

        return response()->json($laporans);
    }

    //Menyimpan laporan medis baru
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
            $image->storeAs('public/laporan_medis', $image->hashName());
        }

        $laporan = LaporanMedis::create([
            'foto'     => $image->hashName(),
            'jenis'    => $request->jenis,
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'lokasi'   => $request->lokasi,
            'tanggal'  => $request->tanggal,
            'isi'      => $request->isi,
        ]);

        $laporan->foto = url('storage/laporan_medis/' . $laporan->foto);

        return response()->json(['success' => "Laporan medis berhasil ditambahkan!"], 201);
    }


    //Menampilkan laporan medis berdasarkan ID
    public function show($id)
    {
        $laporan = LaporanMedis::find($id);
        return response()->json($laporan);
    }

    public function update(Request $request, $id)
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

        $laporan = LaporanMedis::find($id);

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('public/laporan_medis', $image->hashName());

            if ($laporan->foto) {
                Storage::delete('public/laporan_medis/' . $laporan->foto);
            }
            $laporan->foto = $image->hashName();
        }

        $laporan->update($request->except('foto') + ['foto' => $laporan->foto]);

        $laporan->foto = url('storage/laporan_medis/' . $laporan->foto);

        return response()->json(['success' => "Laporan medis berhasil diupdate!"], 200);
    }

    //Menghapus laporan medis berdasarkan ID
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $laporan = LaporanMedis::findOrFail($id);
        $laporan->delete();
        return response()->json(['success' => 'Laporan medis berhasil dihapus'], 200);
    }
}
