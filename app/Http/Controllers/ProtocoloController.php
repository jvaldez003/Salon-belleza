<?php

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\ProtocoloMedio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProtocoloController extends Controller
{
    public function index()
    {
        $protocolos = Protocolo::withCount('medios')->orderBy('orden')->get();
        return view('protocolos.index', compact('protocolos'));
    }

    public function create()
    {
        return view('protocolos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:2000',
        ]);

        $orden = Protocolo::max('orden') + 1;
        $protocolo = Protocolo::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'orden'       => $orden,
            'activo'      => true,
        ]);

        return redirect()->route('protocolos.edit', $protocolo)->with('success', 'Protocolo creado. Ahora agrega imágenes y videos.');
    }

    public function edit(Protocolo $protocolo)
    {
        $protocolo->load('medios');
        return view('protocolos.edit', compact('protocolo'));
    }

    public function update(Request $request, Protocolo $protocolo)
    {
        $request->validate([
            'nombre'      => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:2000',
            'activo'      => 'boolean',
        ]);

        $protocolo->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo'      => $request->boolean('activo'),
        ]);

        return back()->with('success', 'Protocolo actualizado.');
    }

    public function destroy(Protocolo $protocolo)
    {
        foreach ($protocolo->medios as $medio) {
            if (!$medio->esUrlExterna()) {
                Storage::disk('public')->delete($medio->url);
            }
        }
        $protocolo->delete();

        return redirect()->route('protocolos.index')->with('success', 'Protocolo eliminado.');
    }

    /** Agrega una imagen al protocolo. */
    public function storeMedio(Request $request, Protocolo $protocolo)
    {
        $request->validate([
            'tipo'        => 'required|in:imagen,video',
            'archivo'     => 'nullable|file|max:51200|mimes:jpeg,png,jpg,gif,webp,mp4,mov,webm',
            'video_url'   => 'nullable|url|max:500',
        ]);

        $orden = ProtocoloMedio::where('protocolo_id', $protocolo->id)->count();

        if ($request->tipo === 'imagen' && $request->hasFile('archivo')) {
            $url = $request->file('archivo')->store('protocolos', 'public');
            ProtocoloMedio::create(['protocolo_id' => $protocolo->id, 'tipo' => 'imagen', 'url' => $url, 'orden' => $orden]);

        } elseif ($request->tipo === 'video' && $request->hasFile('archivo')) {
            $url = $request->file('archivo')->store('protocolos/videos', 'public');
            ProtocoloMedio::create(['protocolo_id' => $protocolo->id, 'tipo' => 'video', 'url' => $url, 'orden' => $orden]);

        } elseif ($request->tipo === 'video' && $request->filled('video_url')) {
            $url = $this->convertirUrlYoutube($request->video_url);
            ProtocoloMedio::create(['protocolo_id' => $protocolo->id, 'tipo' => 'video', 'url' => $url, 'orden' => $orden]);
        }

        return back()->with('success', 'Medio agregado correctamente.');
    }

    /** Elimina un medio del protocolo. */
    public function destroyMedio(ProtocoloMedio $medio)
    {
        if (!$medio->esUrlExterna()) {
            Storage::disk('public')->delete($medio->url);
        }
        $medio->delete();

        return back()->with('success', 'Medio eliminado.');
    }

    /** Convierte URLs de YouTube a formato embed. */
    private function convertirUrlYoutube(string $url): string
    {
        // youtube.com/watch?v=ID
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        // youtu.be/ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        return $url;
    }
}
