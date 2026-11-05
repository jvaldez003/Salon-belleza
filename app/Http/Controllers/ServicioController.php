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
        $servicios  = Servicio::with(['imagenes', 'categoria'])->orderBy('nombre')->get();
        $categorias = Categoria::orderBy('orden')->orderBy('nombre')->get();
        return view('servicios.index', compact('servicios', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('orden')->orderBy('nombre')->get();
        return view('servicios.create', compact('categorias'));
    }

    /** Reglas compartidas por crear y editar. Fotos de hasta 5 MB (las del celular suelen pesar 3-5 MB). */
    protected function reglas(): array
    {
        return [
            'nombre'       => 'required|string|max:60',
            'precio'       => 'required|numeric|min:0',
            'duracion'     => 'required|integer|min:5|max:600',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagenes.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
    }

    protected function mensajes(): array
    {
        return [
            'duracion.required' => 'Indica cuánto dura el servicio, en minutos.',
            'imagenes.*.max'    => 'Cada foto puede pesar máximo 5 MB.',
            'imagenes.*.mimes'  => 'Las fotos deben ser JPG, PNG, GIF o WebP.',
        ];
    }

    /** Datos del formulario listos para guardar. */
    protected function datos(Request $request): array
    {
        return $request->only(['nombre', 'precio', 'duracion', 'descripcion', 'categoria_id'])
            + ['activo' => $request->boolean('activo')];
    }

    public function store(Request $request)
    {
        $request->validate($this->reglas(), $this->mensajes());

        $servicio = Servicio::create($this->datos($request));

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
        $request->validate($this->reglas(), $this->mensajes());

        $servicio = Servicio::findOrFail($id);
        $servicio->update($this->datos($request));

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
