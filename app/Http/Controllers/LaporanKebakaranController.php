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
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'foto'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jenis'    => 'required|string',
            'nama'     => 'required|string',
            'telepon'  => 'required|string',
            'lokasi'   => 'required|string',
            'tanggal'  => 'required|date',
            'isi'      => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Upload image
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('public/laporan_kebakaran', $image->hashName());
        }

        // Create laporan kebakaran
        $laporan = LaporanKebakaran::create([
            'foto'     => $image->hashName(),
            'jenis'    => $request->jenis,
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'lokasi'   => $request->lokasi,
            'tanggal'  => $request->tanggal,
            'isi'      => $request->isi,
        ]);

        // Format foto URL
        $laporan->foto = url('storage/laporan_kebakaran/' . $laporan->foto);

        // Return response
        return response()->json(['success' => true, 'message' => 'Laporan kebakaran berhasil ditambahkan!', 'data' => $laporan], 201);
    }


    // Menampilkan laporan kebakaran tertentu
    public function show($id)
    {
        $laporan = LaporanKebakaran::findOrFail($id);
        return response()->json($laporan);
    }

    // Mengupdate laporan kebakaran tertentu
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'foto'     => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jenis'    => 'sometimes|required|string',
            'nama'     => 'sometimes|required|string',
            'telepon'  => 'sometimes|required|string',
            'lokasi'   => 'sometimes|required|string',
            'tanggal'  => 'sometimes|required|date',
            'isi'      => 'sometimes|required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find laporan kebakaran
        $laporan = LaporanKebakaran::findOrFail($id);

        // Upload new image if provided
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('public/laporan_kebakaran', $image->hashName());
            // Delete old image if exists
            if ($laporan->foto) {
                Storage::delete('public/laporan_kebakaran/' . $laporan->foto);
            }
            $laporan->foto = $image->hashName();
        }

        // Update laporan kebakaran
        $laporan->update($request->except('foto') + ['foto' => $laporan->foto]);

        // Format foto URL
        $laporan->foto = url('storage/laporan_kebakaran/' . $laporan->foto);

        return response()->json($laporan);
    }




    // Menghapus laporan kebakaran 
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $laporan = LaporanKebakaran::findOrFail($id);
        $laporan->delete();
        return response()->json(['success' => true, 'message' => 'Laporan kebakaran berhasil dihapus'], 200);
    }
}
