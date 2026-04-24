<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\ServicioImagen;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador ServicioController
 * 
 * Gestiona todas las operaciones lógicas relacionadas con el catálogo de servicios:
 * Listado, creación, edición, actualización y eliminación (CRUD).
 */
class ServicioController extends Controller
{
    /**
     * Muestra la lista completa de servicios.
     * Carga los servicios junto con sus imágenes relacionadas para optimizar la consulta.
     */
    public function index()
    {
        $servicios = Servicio::with('imagenes')->get();
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Procesa el almacenamiento de un nuevo servicio en la base de datos.
     * Incluye validación de campos y manejo de múltiples archivos de imagen.
     */
    public function store(Request $request)
    {
        // Validación de datos de entrada según requerimientos técnicos
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Crea el registro base del servicio
        $servicio = Servicio::create($request->only(['nombre', 'precio', 'descripcion']));

        // Si se subieron imágenes, las guarda en el disco 'public' y crea la relación en la DB
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('servicios', 'public');
                $servicio->imagenes()->create(['url' => $path]);
            }
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un servicio existente.
     */
    public function edit($id)
    {
        $servicio = Servicio::with('imagenes')->findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Procesa la actualización de los datos de un servicio.
     * Permite modificar datos básicos y añadir nuevas imágenes a la galería actual.
     */
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

        // Manejo de nuevas imágenes subidas durante la edición
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('servicios', 'public');
                $servicio->imagenes()->create(['url' => $path]);
            }
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Elimina un servicio por completo.
     * Borra también los archivos físicos de imagen del disco para ahorrar espacio.
     */
    public function destroy($id)
    {
        $servicio = Servicio::with('imagenes')->findOrFail($id);
        
        // Limpieza de archivos físicos antes de borrar la relación en DB
        foreach ($servicio->imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->url);
        }
        
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }

    /**
     * Elimina una imagen específica de la galería de un servicio.
     * Útil para la gestión individual de fotos en la vista de edición.
     */
    public function eliminarImagen($id)
    {
        $imagen = ServicioImagen::findOrFail($id);
        Storage::disk('public')->delete($imagen->url);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}
