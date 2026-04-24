<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('banners.index', compact('banners'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240|dimensions:min_width=1200,min_height=600',
            'subtitulo' => 'nullable|string',
        ]);

        $path = $request->file('imagen')->store('banners', 'public');

        Banner::create([
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'imagen_url' => $path,
            'texto_boton' => $request->texto_boton,
            'link_boton' => $request->link_boton,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner creado correctamente.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10240|dimensions:min_width=1200,min_height=600',
        ]);

        $data = [
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'texto_boton' => $request->texto_boton,
            'link_boton' => $request->link_boton,
            'activo' => $request->has('activo'),
        ];

        if ($request->hasFile('imagen')) {
            Storage::disk('public')->delete($banner->imagen_url);
            $data['imagen_url'] = $request->file('imagen')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::disk('public')->delete($banner->imagen_url);
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner eliminado correctamente.');
    }
}
