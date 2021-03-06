<?php

namespace ReRe\Rere\Controller;

/**
 *
 * Beinhaltet alle Funktionen für den Export von Prüflingen, Modulen und Fächern.
 *
 * @author Felix
 */
class ExportController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    const MODUL = 'modul';
    const FACH = 'fach';
    const MATRIKELNR = 'matrikelnr';
    const VORNAME = 'prueflingvorname';
    const NACHNAME = 'prueflingnachname';
    const FACHNAME = 'Fachname';
    const DATUM = 'Prüfungsdatum';
    const NOTENSCHEMA = 'Notenschema';

    /**
     * Protected Variable helper wird mit NULL initialisiert.
     *
     * @var \ReRe\Rere\Domain\Repository\NoteRepository
     * @inject
     */
    protected $noteRepository = NULL;

    /**
     * Protected Variable prueflingRepository wird mit NULL initialisiert.
     *
     * @var \ReRe\Rere\Domain\Repository\PrueflingRepository
     * @inject
     */
    protected $prueflingRepository = NULL;

    /**
     * Protected Variable modulRepository wird mit NULL initialisiert.
     *
     * @var \ReRe\Rere\Domain\Repository\ModulRepository
     * @inject
     */
    protected $modulRepository = NULL;

    /**
     * Protected Variable fachRepository wird mit NULL initialisiert.
     *
     * @var \ReRe\Rere\Domain\Repository\FachRepository
     * @inject
     */
    protected $fachRepository = NULL;

    /**
     * Private Klassenvariable für die Hilfsklassen wird mit NULL initialisiert.
     *
     * @var type
     */
    private $exportHelper = NULL;

    /**
     * Protected Variable FrontendUserRepository wird mit NULL initialisiert.
     *
     * @var \Typo3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $FrontendUserRepository = NULL;

    /**
     * Im Konstruktor des ExportControllers wird eine Instanz der ExportHelperKlasse erzeugt.
     */
    public function __construct() {
        $this->exportHelper = new \ReRe\Rere\Services\NestedDirectory\ExportHelper();
    }

    /**
     * @return void
     */
    public function exportPrueflingeAction() {
        $preuflinge = $this->prueflingRepository->findAll();
        $out = array();
        array_push($out, array(self::MATRIKELNR => "Matrikelnummer", self::VORNAME => "Vorname", self::NACHNAME => "Nachname", 'mail' => "Email-Adresse", 'username' => "Username", 'pass' => "Passwort"));
        foreach ($preuflinge as $pruefling) {
            // Generiert Ausgabe-Array mit Prüfling- und Noten-Daten
            $feUser = $pruefling->getTypo3FEUser();
            array_push($out, array(self::MATRIKELNR => $pruefling->getMatrikelnr(), self::VORNAME => $pruefling->getVorname(), self::NACHNAME => $pruefling->getNachname(), 'mail' => $feUser->getEmail(), 'username' => $feUser->getUsername(), 'pass' => $feUser->getPassword()));
        }

        // Export wird gestartet
        $this->exportHelper->genCSV($out, "Prueflinge.csv");
        return false;
    }

    /**
     * Exportiert alle Modulle und alle Fächer
     * @return void
     */
    public function exportModuleUndFaecherAction() {
        $fachs = $this->fachRepository->findAll();
        $out = array();

        array_push($out, array('FachNr' => "Fachnummer", self::FACHNAME => self::FACHNAME,
            'pruefer' => "Prüfer", self::NOTENSCHEMA => self::NOTENSCHEMA, self::DATUM => self::DATUM, 'ModulUid' => "Modul Uid",
            'ModulNr' => "Modulnummer", 'ModulName' => "Modulname",
            'Gueltigkeitszeitraum' => "Gültigkeitszeitraum"));

        foreach ($fachs as $fach) {
            array_push($out, array('FachNr' => $fach->getFachnr(), self::FACHNAME => $fach->getFachname(),
                'pruefer' => $fach->getPruefer(), self::NOTENSCHEMA => $fach->getNotenschema(),
                self::DATUM => $fach->getDatum(), 'ModulUid' => $fach->getModulnr(),
                'ModulNr' => $this->modulRepository->findByUid($fach->getModulnr())->getModulnr(),
                'ModulName' => $this->modulRepository->findByUid($fach->getModulnr())->getModulname(),
                'Gueltigkeitszeitraum' => $this->modulRepository->findByUid($fach->getModulnr())->getGueltigkeitszeitraum()));
        }

        // Export wird gestartet
        $this->exportHelper->genCSV($out, "ModuleUndFaecher.csv");
        return false;
    }

    /**
     * Exportiert alle Noten eines Faches.
     * @return void Description
     */
    public function exportFachAction() {
        if ($this->request->hasArgument('fachuid')) {
            $fach = $this->fachRepository->findByUid($this->request->getArgument('fachuid'));
        }

        // Holen aller eingetragener Noten
        $notes = $this->noteRepository->findAll();
        $publisharray = array();

        array_push($publisharray, array(self::MATRIKELNR => "Matrikelnummer", self::VORNAME => "Vorname", self::NACHNAME => "Nachname", 'wert' => "Wert", 'kommentar' => "Kommentar", self::DATUM => self::DATUM,));

        foreach ($notes as $note) {
            if ($note->getFach() == $fach->getUid()) {
                // Holt den Prüfling, dem die Note zugewiesen wurde
                $pruefling = $this->prueflingRepository->findByUid($note->getPruefling());
                // Generiert Ausgabe-Array mit Prüfling- und Noten-Daten
                array_push($publisharray, array(self::MATRIKELNR => $pruefling->getMatrikelnr(), self::VORNAME => $pruefling->getVorname(), self::NACHNAME => $pruefling->getNachname(), 'wert' => $note->getWert(), 'kommentar' => $note->getKommentar(), self::DATUM => $fach->getDatum()));
            }
        }

        // Export wird gestartet
        $this->exportHelper->genCSV($publisharray, "FachExport.csv");
        return false;
    }

}
