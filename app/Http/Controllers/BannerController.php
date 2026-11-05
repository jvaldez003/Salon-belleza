<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador BannerController
 * 
 * Gestiona los banners publicitarios de la página de inicio.
 * Permite configurar la imagen de fondo, títulos y botones de acción del carrusel.
 */
class BannerController extends Controller
{
    /**
     * Muestra el listado de todos los banners registrados.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('banners.index', compact('banners'));
    }

    /**
     * Muestra el formulario para añadir un nuevo banner.
     */
    public function create()
    {
        return view('banners.create');
    }

    /**
     * Almacena un nuevo banner.
     * Valida que la imagen tenga una resolución mínima para garantizar la calidad visual.
     */
    public function store(Request $request)
    {
        $request->validate($this->reglas(true), $this->mensajes());

        // Almacenamiento de la imagen en el disco público
        $path = $request->file('imagen')->store('banners', 'public');

        Banner::create([
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'imagen_url' => $path,
            'texto_boton' => $request->texto_boton,
            'link_boton' => $request->link_boton,
            'activo' => $request->has('activo'), // El checkbox devuelve true si está marcado
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner creado correctamente.');
    }

    /**
     * Muestra el formulario de edición para un banner específico.
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
    }

    /**
     * Actualiza la información del banner.
     * Si se sube una nueva imagen, elimina la anterior para optimizar almacenamiento.
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate($this->reglas(false), $this->mensajes());

        $data = [
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'texto_boton' => $request->texto_boton,
            'link_boton' => $request->link_boton,
            'activo' => $request->has('activo'),
        ];

        // Solo procesamos la imagen si el usuario seleccionó una nueva
        if ($request->hasFile('imagen')) {
            // Borramos la imagen antigua para no dejar archivos huérfanos
            Storage::disk('public')->delete($banner->imagen_url);
            $data['imagen_url'] = $request->file('imagen')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    /**
     * Reglas del banner. La foto se muestra en un arco vertical (4:5) en el inicio,
     * así que se aceptan fotos verticales o cuadradas de al menos 800×800 px.
     */
    protected function reglas(bool $fotoObligatoria): array
    {
        return [
            'titulo'      => 'required|string|max:255',
            'subtitulo'   => 'nullable|string|max:400',
            'texto_boton' => 'nullable|string|max:40',
            'link_boton'  => 'nullable|string|max:255',
            'imagen'      => ($fotoObligatoria ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:min_width=800,min_height=800',
        ];
    }

    protected function mensajes(): array
    {
        return [
            'imagen.required'   => 'Elige la foto del banner.',
            'imagen.dimensions' => 'La foto debe medir al menos 800 × 800 píxeles para verse nítida.',
            'imagen.max'        => 'La foto puede pesar máximo 10 MB.',
            'imagen.mimes'      => 'La foto debe ser JPG, PNG o WebP.',
            'subtitulo.max'     => 'El texto puede tener máximo 400 caracteres; en el inicio se recorta a 4 líneas.',
        ];
    }

    /**
     * Elimina permanentemente un banner y su archivo de imagen asociado.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::disk('public')->delete($banner->imagen_url);
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner eliminado correctamente.');
    }
}
