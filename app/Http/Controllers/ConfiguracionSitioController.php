<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionSitio;
use App\Models\SeccionImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionSitioController extends Controller
{
    public function edit()
    {
        $config = ConfiguracionSitio::instancia();
        $imagenesPorSeccion = SeccionImagen::orderBy('orden')
            ->get()
            ->groupBy('seccion');

        return view('admin.configuracion', compact('config', 'imagenesPorSeccion'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre_negocio'        => 'required|string|max:100',
            'logo'                  => 'nullable|image|max:2048',
            'quienes_somos_titulo'  => 'nullable|string|max:100',
            'quienes_somos_texto'   => 'nullable|string|max:2000',
            'quienes_somos_imagen'  => 'nullable|image|max:3072',
            'mision_texto'          => 'nullable|string|max:1000',
            'mision_imagen'         => 'nullable|image|max:3072',
            'vision_texto'          => 'nullable|string|max:1000',
            'vision_imagen'         => 'nullable|image|max:3072',
            'telefono'              => 'nullable|string|max:30',
            'email_contacto'        => 'nullable|email|max:100',
            'direccion'             => 'nullable|string|max:200',
        ]);

        $config = ConfiguracionSitio::instancia();
        $data   = $request->only([
            'nombre_negocio', 'quienes_somos_titulo', 'quienes_somos_texto',
            'mision_texto', 'vision_texto', 'telefono', 'email_contacto', 'direccion',
        ]);

        foreach (['logo', 'quienes_somos_imagen', 'mision_imagen', 'vision_imagen'] as $campo) {
            if ($request->hasFile($campo)) {
                if ($config->$campo) Storage::disk('public')->delete($config->$campo);
                $data[$campo] = $request->file($campo)->store('sitio', 'public');
            }
        }

        $config->update($data);

        return redirect()->route('configuracion.edit')->with('success', 'Configuración guardada correctamente.');
    }

    public function storeImagen(Request $request)
    {
        $request->validate([
            'seccion' => 'required|in:quienes_somos,mision,vision',
            'imagen'  => 'required|image|max:4096',
        ]);

        $orden = SeccionImagen::where('seccion', $request->seccion)->count();
        $url   = $request->file('imagen')->store('sitio/secciones', 'public');

        SeccionImagen::create([
            'seccion'    => $request->seccion,
            'imagen_url' => $url,
            'orden'      => $orden,
        ]);

        return back()->with('success', 'Imagen agregada correctamente.');
    }

    public function destroyImagen(SeccionImagen $imagen)
    {
        Storage::disk('public')->delete($imagen->imagen_url);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada.');
    }
}
