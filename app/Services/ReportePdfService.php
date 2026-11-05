<?php

namespace App\Services;

/**
 * PDF de texto simple (sin dependencias). Tamaño carta, Helvetica con codificación
 * WinAnsi para que las tildes y la ñ salgan bien, y varias páginas si hace falta.
 */
class ReportePdfService
{
    private const LINEAS_POR_PAGINA = 50;

    public function generar(string $titulo, array $lineas): string
    {
        $paginas = array_chunk($lineas, self::LINEAS_POR_PAGINA) ?: [[]];
        $total   = count($paginas);

        // Objetos: 1 catálogo, 2 páginas, 3 fuente normal, 4 fuente negrita, luego (página, contenido) por cada hoja.
        $objetos = [];
        $kids    = [];
        foreach ($paginas as $i => $grupo) {
            $pagId = 5 + $i * 2;
            $kids[] = "{$pagId} 0 R";

            $ops  = "BT /F2 15 Tf 50 750 Td (" . $this->escapar($titulo) . ") Tj ET\n";
            $ops .= "BT /F1 9 Tf 50 734 Td (" . $this->escapar('Página ' . ($i + 1) . ' de ' . $total) . ") Tj ET\n";
            $ops .= "BT /F1 10 Tf 13.5 TL 50 708 Td\n";
            foreach ($grupo as $n => $linea) {
                if ($n > 0) $ops .= "T*\n";
                $ops .= '(' . $this->escapar((string) $linea) . ") Tj\n";
            }
            $ops .= "ET";

            $objetos[$pagId]     = "<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents " . ($pagId + 1) . " 0 R/Resources<</Font<</F1 3 0 R/F2 4 0 R>>>>>>";
            $objetos[$pagId + 1] = "<</Length " . strlen($ops) . ">>stream\n{$ops}\nendstream";
        }
        $objetos[1] = "<</Type/Catalog/Pages 2 0 R>>";
        $objetos[2] = "<</Type/Pages/Kids[" . implode(' ', $kids) . "]/Count {$total}>>";
        $objetos[3] = "<</Type/Font/Subtype/Type1/BaseFont/Helvetica/Encoding/WinAnsiEncoding>>";
        $objetos[4] = "<</Type/Font/Subtype/Type1/BaseFont/Helvetica-Bold/Encoding/WinAnsiEncoding>>";
        ksort($objetos);

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objetos as $id => $cuerpo) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj{$cuerpo}\nendobj\n";
        }

        $xref = strlen($pdf);
        $cantidad = count($objetos) + 1;
        $pdf .= "xref\n0 {$cantidad}\n0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }
        $pdf .= "trailer<</Size {$cantidad}/Root 1 0 R>>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
    }

    protected function escapar(string $texto): string
    {
        $texto = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $texto) ?: $texto;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $texto);
    }
}
