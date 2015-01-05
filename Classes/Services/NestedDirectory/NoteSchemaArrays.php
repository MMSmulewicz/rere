<?php

namespace ReRe\Rere\Services\NestedDirectory;

/**
 * Speichert die verschiedenen Noten-Systeme für die Notenauswahl bei der Notenvergabe.
 *
 * @author Felix
 */
class NoteSchemaArrays {

    const BITTE = "Bitte wählen";

    /**
     * Array für XYZ Notensystem.
     * @var type array
     */
    protected $hochschulmarks = array(
        '0' => self::BITTE,
        '1.0' => '1.0',
        '1.3' => '1.3',
        '1.7' => '1.7',
        '2.0' => '2.0',
        '2.3' => '2.3',
        '2.7' => '2.7',
        '3.0' => '3.0',
        '3.3' => '3.3',
        '3.7' => '3.7',
        '4.0' => '4.0',
        '5.0' => '5.0'
    );

    /**
     * Array für XYZ Noten-System.
     * @var type
     */
    protected $schoolMarks = array(
        '0' => self::BITTE,
        '1.0' => '1.0',
        '1.1' => '1.1',
        '1.2' => '1.2',
        '1.3' => '1.3',
        '1.4' => '1.4',
        '1.5' => '1.5',
        '1.6' => '1.6',
        '1.7' => '1.7',
        '1.8' => '1.8',
        '1.9' => '1.9',
        '2.0' => '2.0',
        '2.1' => '2.1',
        '2.2' => '2.2',
        '2.3' => '2.3',
        '2.4' => '2.4',
        '2.5' => '2.5',
        '2.6' => '2.6',
        '2.7' => '2.7',
        '2.8' => '2.8',
        '2.9' => '2.9',
        '3.0' => '3.0',
        '3.1' => '3.1',
        '3.2' => '3.2',
        '3.3' => '3.3',
        '3.4' => '3.4',
        '3.5' => '3.5',
        '3.6' => '3.6',
        '3.7' => '3.7',
        '3.8' => '3.8',
        '3.9' => '3.9',
        '4.0' => '4.0',
        '4.1' => '4.1',
        '4.2' => '4.2',
        '4.3' => '4.3',
        '4.4' => '4.4',
        '4.5' => '4.5',
        '4.6' => '4.6',
        '4.7' => '4.7',
        '4.8' => '4.8',
        '4.9' => '4.9',
        '5.0' => '5.0',
        '5.1' => '5.1',
        '5.2' => '5.2',
        '5.3' => '5.3',
        '5.4' => '5.4',
        '5.5' => '5.5',
        '5.6' => '5.6',
        '5.7' => '5.7',
        '5.8' => '5.8',
        '5.9' => '5.9',
        '6.0' => '6.0'
    );

    /**
     * Array für unbenotete Leistungen.
     * @var type
     */
    protected $unbenotetMarks = array(
        '0' => self::BITTE,
        'be' => 'be',
        'N' => 'N'
    );

    /**
     * Array für das 15-Punkte-System.
     * @var type
     */
    protected $fifteenMarks = array(
        '0' => self::BITTE,
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
        '12' => '12',
        '13' => '13',
        '14' => '14',
        '15' => '15'
    );

    /**
     *
     * Diese Methode setzt das System entsprechend dem übergebenen Schema.
     *
     * @return array
     */
    public function getMarkArray($schema) {
        if ($schema == "hochschulsystem") {
            $array = $this->hochschulmarks;
        } elseif ($schema == "15pktsystem") {
            $array = $this->fifteenMarks;
        } elseif ($schema == "schulsystem") {
            $array = $this->schoolMarks;
        } else {
            $array = $this->unbenotetMarks;
        }

        return $array;
    }

}
