<?php

namespace App\Services;

class ReportePdfService
{
    public function generar(string $titulo, array $lineas): string
    {
        $texto = array_merge([$titulo, ''], $lineas);
        $ops = "BT /F1 11 Tf 14 TL 50 760 Td\n";

        foreach ($texto as $i => $linea) {
            if ($i > 0) {
                $ops .= "T*\n";
            }
            $ops .= '('.$this->escapar($linea).") Tj\n";
        }

        $ops .= 'ET';
        $len = strlen($ops);

        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n";
        $pdf .= "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n";
        $pdf .= "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents 4 0 R/Resources<</Font<</F1 5 0 R>>>>>>endobj\n";
        $pdf .= "4 0 obj<</Length {$len}>>stream\n{$ops}endstream\nendobj\n";
        $pdf .= "5 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n";
        $xref = strlen($pdf);
        $pdf .= "xref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000350 00000 n \n";
        $pdf .= "trailer<</Size 6/Root 1 0 R>>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
    }

    protected function escapar(string $texto): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $texto);
    }
}
