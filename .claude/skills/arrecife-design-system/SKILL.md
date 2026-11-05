---
name: arrecife-design-system
description: Sistema de diseño de Arrecife Beauty Studio (Laravel + Blade + Tailwind + Alpine). Úsala SIEMPRE antes de crear, rediseñar o migrar cualquier vista Blade, componente, estilo o pantalla del sitio público o del panel admin de este proyecto, y cuando se trabaje con fotos que sube el salón, precios o textos de la base de datos.
---

# Sistema de diseño Arrecife

Concepto: **"descenso al arrecife"**. La página empieza clara (superficie) y baja hacia el azul abismo. Un solo elemento audaz: el reflejo de luz de agua sobre la foto principal del inicio. Todo lo demás es sobrio.

## Tokens (tailwind.config.js)

| Clase Tailwind | Hex | Uso |
|---|---|---|
| `superficie` | #F8FCFF | Fondo principal |
| `bruma` / `bruma-honda` | #EBF5FC / #D9EAF2 | Fondos de sección, fondo detrás de fotos |
| `laguna` | #008FE8 | Enlaces, foco, acentos pequeños. **No** como fondo de botón con texto blanco (contraste insuficiente) |
| `agua` | #7FD8FF | Botones y detalles **sobre fondo abismo** |
| `oceano` | #0E4A9E | Botones primarios, precios |
| `abismo` / `tinta` | #0A2F6B | Títulos, secciones oscuras, pie de página |
| `texto` / `texto-suave` | #2C4468 / #546C8C | Párrafos / texto secundario |
| `linea` | #C5DAE8 | Bordes y divisores |
| `sobre-abismo` / `sobre-abismo-suave` | #EAF6FF / #A9C6E4 | Texto sobre fondo abismo |

**Prohibido:** colores hex sueltos (`bg-[#...]`, `style="color:#..."`) y los grises/índigo por defecto de Tailwind (`gray-*`, `indigo-*`). Los alias viejos `rose`/`gold`/`cream`/`warm` ya no existen. Excepción: estados semánticos (verde completada, rojo cancelada/peligro).

Tipografía: `font-display` = Bodoni Moda (títulos, precios, logotipo), `font-sans` = Manrope (todo lo demás). Cargadas en `resources/views/partials/fuentes.blade.php`. Títulos con peso 500, no `font-black`.

## Componentes Blade (resources/views/components)

- `<x-foto :src proporcion foco inicial alt prioridad>`: **toda** foto subida por el salón va aquí. Proporciones: `4/5` tarjetas, `3/4` retratos, `1/1` galería, `16/9` banners (en móvil usar `class="aspect-4/5 md:aspect-16/9"`). `foco`: arriba/centro/abajo. Sin foto muestra un respaldo de marca con la inicial. Texto encima de la foto: slot con `<div class="foto-velo">`.
- `<x-boton href variante>`: primario · secundario · claro (sobre abismo) · peligro. Sin `href` es `<button>` (por defecto `type="button"`).
- `<x-precio :valor desde>`: pesos colombianos redondeados ($30.000), números alineados.
- `<x-marca tono>`: concha del logo (`public/img/logo-concha.png`) + nombre en Bodoni. `tono="claro"` sobre abismo. El logo completo (`img/logo-completo.png`) solo va sobre fondos claros.
- Hero del inicio (`welcome.blade.php`, `resources/js/hero.js` + `hero-agua.js`): foto en WebGL con transición de onda de agua, acercamiento lento, cáusticas y textos que emergen palabra por palabra. Es el **único** elemento audaz del sitio: no copiar sus animaciones en otras secciones. `<x-luz-agua />` queda como respaldo sin WebGL.
- Panel admin: `<x-aviso />` (mensajes de sesión), `<x-estado-cita :estado>`, `<x-confirmar-borrado :action que>` (nunca `confirm()` del navegador), `<x-subir-fotos name marcos>` (vista previa del recorte por marco), `<x-horario-semana :dias>`.
- Números: precios con `<x-precio>`/Bodoni; **conteos** (citas, clientes, totales) en Manrope semibold con `tabular`, porque en Bodoni el 1 parece una I y el 0 parece ().
- Animaciones al bajar (sitio público, `resources/js/revelar.js`): atributos `data-revelar="sube|palabras|foto|linea|lectura"` con `style="--d: 200ms"` para escalonar; `data-paralaje="0.1"` en fotos para profundidad; `data-magnetico` en botones de cierre. Fondos abismo: `<x-abismo-luz />` (rayos y partículas; `:rayos="false"` solo partículas). El pie de página termina en `<x-arrecife-fondo>` (arrecife ilustrado en 5 capas con volumen, fauna animada, marcos laterales y medusas; el pie lleva `data-raton-profundidad` y un `.arrecife-velo` detrás del texto para que se lea): es el final del descenso, no repetirlo en otras secciones. Úsalas con medida: una sola animación por elemento, nada de efectos en el panel admin.
- Paginación: `->links()` usa `partials/paginacion` automáticamente. Páginas de error en `resources/views/errors` (sin base de datos).

Clases CSS (resources/css/app.css): `btn-primary`, `btn-secondary`, `btn-claro`, `btn-peligro`, `card`, `form-input`, `label`, `badge-*`, `alert-*`, `foto-marco`, `foto-velo`, `foto-respaldo`, `precio`, `tabular`.

## Reglas

1. **Fotos del salón:** nunca `<img>` suelto con alto fijo; siempre `<x-foto>` con marco de proporción fija y `object-cover`. Nunca texto sobre foto sin `foto-velo`.
2. **Textos de la base de datos:** muchos están en MAYÚSCULAS. Muéstralos con `Str::legible($texto)` (macro en AppServiceProvider). No uses `uppercase` para etiquetas.
3. **Precios:** formato carta (nombre a la izquierda, `<x-precio>` a la derecha, línea `border-linea` entre filas). Si el nombre del servicio contiene "desde", quítalo del nombre y usa `<x-precio desde>`.
4. **Estructura:** contenedor `max-w-arrecife mx-auto px-4 sm:px-6`. Textos alineados a la izquierda. Secciones encadenan el descenso: `superficie → bruma → bruma-honda → abismo`; la reserva de cita y el pie siempre en `bg-abismo`.
5. **Tarjetas con moderación:** borde `border-linea` y `rounded-2xl` solo para objetos realmente separados. Sin sombras grises genéricas en todo. Botones en píldora (`rounded-full`). Fotos con `rounded-foto` (14px).
6. **Movimiento:** nada de fade-in en cada sección ni animaciones en cada tarjeta. Solo transiciones que respondan a una acción (hover, abrir, confirmar). Respetar `prefers-reduced-motion`.
7. **Textos de interfaz:** español claro, voz activa, oraciones en minúscula normal. Botones dicen lo que hacen: "Reservar cita", "Guardar cambios". Nada de emojis como íconos.
8. **Accesibilidad:** foco visible (ya global), contraste AA, `alt` en fotos con contenido, diseño pensado primero para el celular.

## Admin vs. público

El panel admin usa los mismos tokens y componentes, con un tono más funcional: fondo `superficie`, tablas con `border-linea`, títulos en Bodoni solo a nivel de página, sin efecto de luz de agua ni el descenso de color.
