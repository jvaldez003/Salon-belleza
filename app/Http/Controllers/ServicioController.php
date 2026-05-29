<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\ServicioImagen;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with(['imagenes', 'categoria'])->get();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('orden')->orderBy('nombre')->get();
        return view('servicios.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:60',
            'precio'       => 'required|numeric|min:0',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagenes.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $servicio = Servicio::create($request->only(['nombre', 'precio', 'descripcion', 'categoria_id']));

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('servicios', 'public');
                $servicio->imagenes()->create(['url' => $path]);
            }
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit($id)
    {
        $servicio    = Servicio::with('imagenes')->findOrFail($id);
        $categorias  = Categoria::orderBy('orden')->orderBy('nombre')->get();
        return view('servicios.edit', compact('servicio', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'       => 'required|string|max:60',
            'precio'       => 'required|numeric|min:0',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagenes.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->only(['nombre', 'precio', 'descripcion', 'categoria_id']));

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('servicios', 'public');
                $servicio->imagenes()->create(['url' => $path]);
            }
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servicio = Servicio::with('imagenes')->findOrFail($id);

        foreach ($servicio->imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->url);
        }

        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }

    public function eliminarImagen($id)
    {
        $imagen = ServicioImagen::findOrFail($id);
        Storage::disk('public')->delete($imagen->url);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}
