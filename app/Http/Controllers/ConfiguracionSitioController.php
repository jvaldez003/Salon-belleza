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
            'eslogan'               => 'nullable|string|max:150',
            'logo'                  => 'nullable|image|max:2048',
            'quienes_somos_titulo'  => 'nullable|string|max:100',
            'quienes_somos_texto'   => 'nullable|string|max:2000',
            'quienes_somos_imagen'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'mision_texto'          => 'nullable|string|max:1000',
            'mision_imagen'         => 'nullable|image|max:3072',
            'vision_texto'          => 'nullable|string|max:1000',
            'vision_imagen'         => 'nullable|image|max:3072',
            'telefono'              => 'nullable|string|max:30',
            'email_contacto'        => 'nullable|email|max:100',
            'direccion'             => 'nullable|string|max:200',
            'whatsapp'              => 'nullable|string|max:30',
            'facebook'              => 'nullable|url|max:200',
            'instagram'             => 'nullable|url|max:200',
            'tiktok'                => 'nullable|url|max:200',
            'youtube'               => 'nullable|url|max:200',
        ]);

        $config = ConfiguracionSitio::instancia();
        $data   = $request->only([
            'nombre_negocio', 'eslogan',
            'quienes_somos_titulo', 'quienes_somos_texto',
            'mision_texto', 'vision_texto',
            'telefono', 'email_contacto', 'direccion',
            'whatsapp', 'facebook', 'instagram', 'tiktok', 'youtube',
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
            'imagen'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'imagen.required' => 'Elige una foto para subir.',
            'imagen.max'      => 'La foto puede pesar máximo 5 MB.',
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
