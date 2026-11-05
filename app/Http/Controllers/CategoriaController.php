<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\CategoriaImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden')->orderBy('nombre')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:80|unique:categorias,nombre',
        ]);

        $orden = Categoria::max('orden') + 1;
        Categoria::create(['nombre' => $request->nombre, 'orden' => $orden]);

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:80|unique:categorias,nombre,' . $categoria->id,
        ]);

        $categoria->update(['nombre' => $request->nombre]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->servicios()->update(['categoria_id' => null]);
        foreach ($categoria->imagenes as $img) {
            Storage::disk('public')->delete($img->url);
        }
        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }

    public function storeImagen(Request $request, Categoria $categoria)
    {
        $request->validate(
            ['imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'],
            ['imagen.max' => 'La foto puede pesar máximo 5 MB.', 'imagen.required' => 'Elige una foto para subir.']
        );

        $path = $request->file('imagen')->store('categorias', 'public');
        $categoria->imagenes()->create([
            'url'   => $path,
            'orden' => (int) $categoria->imagenes()->max('orden') + 1,
        ]);

        return back()->with('success', 'Imagen añadida.');
    }

    public function destroyImagen(CategoriaImagen $imagen)
    {
        Storage::disk('public')->delete($imagen->url);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada.');
    }
}
