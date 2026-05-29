<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
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
        // Desvincula servicios antes de borrar
        $categoria->servicios()->update(['categoria_id' => null]);
        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }

    public function storeImagen(Request $request, Categoria $categoria)
    {
        $request->validate(['imagen' => 'required|image|max:3072']);

        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }

        $path = $request->file('imagen')->store('categorias', 'public');
        $categoria->update(['imagen' => $path]);

        return back()->with('success', 'Imagen de categoría actualizada.');
    }

    public function destroyImagen(Categoria $categoria)
    {
        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
            $categoria->update(['imagen' => null]);
        }

        return back()->with('success', 'Imagen eliminada.');
    }
}
