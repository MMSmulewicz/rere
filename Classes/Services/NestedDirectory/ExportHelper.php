<?php

namespace ReRe\Rere\Services\NestedDirectory;

/**
 * Beinhaltet alle funktionen die für den Download der Exporte benötigt werden.
 *
 * @author Felix
 */
class ExportHelper {

    /**
     * Erzeugt ein CSV File und lädtz dieses herunter.
     * @param type $array
     * @param type $filename
     */
    public function genCSV($array, $filename) {
        // Anlegen eine termporären datei mit Schreibrechten
        $fp = fopen('php://memory', 'w');

        // Array in CSV übertragen
        foreach ($array as $fields) {
            fputcsv($fp, $fields, ";");
        }
        fseek($fp, 0);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="' . $filename . '";');
        // Download starten
        fpassthru($fp);
    }

}