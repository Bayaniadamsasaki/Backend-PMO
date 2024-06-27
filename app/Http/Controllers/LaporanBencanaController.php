<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\LaporanBencana;

class LaporanBencanaController extends Controller
{
    //Menampilkan semua laporan bencana
    public function index()
    {
        $laporans = LaporanBencana::all();

        // Format foto URL
        foreach ($laporans as $laporan) {
            $laporan->foto = url('storage/laporan_bencana/' . $laporan->foto);
        }

        return response()->json($laporans);
    }

    //Menyimpan laporan bencana baru
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
            $image->storeAs('public/laporan_bencana', $image->hashName());
        }

        $laporan = LaporanBencana::create([
            'foto'     => $image->hashName(),
            'jenis'    => $request->jenis,
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'lokasi'   => $request->lokasi,
            'tanggal'  => $request->tanggal,
            'isi'      => $request->isi,
        ]);

        $laporan->foto = url('storage/laporan_bencana/' . $laporan->foto);

        return response()->json(['success' => "Laporan bencana berhasil ditambahkan!"], 201);
    }

    //Menampilkan laporan bencana berdasarkan ID
    public function show($id)
    {
        $laporan = LaporanBencana::find($id);
        return response()->json($laporan);
    }

    //Edit laporan bencana
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

        $laporan = LaporanBencana::find($id);

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('public/laporan_bencana', $image->hashName());

            if ($laporan->foto) {
                # code...
                Storage::delete('public/laporan_bencana/' . $laporan->foto);
            }
            $laporan->foto = $image->hashName();
        }

        $laporan->update($request->except('foto') + ['foto' => $laporan->foto]);

        $laporan->foto = url('storage/laporan_bencana/' . $laporan->foto);

        return response()->json(['success' => "Laporan bencana berhasil diupdate!"], 200);
    }

    //Hapus laporan bencana
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $laporan = LaporanBencana::findOrFail($id);
        $laporan->delete();
        return response()->json(['success' => "Laporan bencana berhasil dihapus!"], 200);
    }
}
