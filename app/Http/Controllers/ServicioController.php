<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\ServicioImagen;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('imagenes')->get();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $servicio = Servicio::create($request->only(['nombre', 'precio', 'descripcion']));

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
        $servicio = Servicio::with('imagenes')->findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->only(['nombre', 'precio', 'descripcion']));

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
