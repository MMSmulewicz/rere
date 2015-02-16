<?php

namespace ReRe\Rere\Tests\Unit\Controller;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Felix Hohlwegler <info@felix-hohlwegler.de>, TeamProjektWS14/15
 *  			Sarah Kieninger <sarah.kieninger@gmail.com>, TeamProjektWS14/15
 *  			Tim Wacker , TeamProjektWS14/15
 *  			Nejat Balta , TeamProjektWS14/15
 *  			Tobias Brockner , TeamProjektWS14/15
 *  			Nicolas Tedjadharma , TeamProjektWS14/15
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Test case for class ReRe\Rere\Controller\FachController.
 *
 * @author Felix Hohlwegler <info@felix-hohlwegler.de>
 * @author Sarah Kieninger <sarah.kieninger@gmail.com>
 * @author Tim Wacker
 * @author Nejat Balta
 * @author Tobias Brockner
 * @author Nicolas Tedjadharma
 */
class FachControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

    const FACHCONTROLLER = "ReRe\\Rere\\Controller\\FachController";
    const FACHREPOSITORY = "ReRe\\Rere\\Domain\\Repository\\FachRepository";
    const MODULREPOSITORY = "ReRe\\Rere\\Domain\\Repository\\ModulRepository";
    const FACHREPO = "fachRepository";
    const VIEWINTERFACE = "TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface";
    const REQUEST = "TYPO3\\CMS\\Extbase\\Mvc\\Request";
    const ASSIGN = "assign";

    /**
     * @var \ReRe\Rere\Controller\FachController
     */
    protected $subject = NULL;

    protected function setUp() {
        $this->subject = $this->getMock(self::FACHCONTROLLER, array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
    }

    protected function tearDown() {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function listActionFetchesAllFachesFromRepositoryAndAssignsThemToView() {

        $allFaches = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

        $fachRepository = $this->getMock(self::FACHREPOSITORY, array('findAll'), array(), '', FALSE);
        $fachRepository->expects($this->once())->method('findAll')->will($this->returnValue($allFaches));
        $this->inject($this->subject, self::FACHREPO, $fachRepository);

        $view = $this->getMock(self::VIEWINTERFACE);
        $view->expects($this->once())->method(self::ASSIGN)->with('faches', $allFaches);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenFachToView() {
        $fach = new \ReRe\Rere\Domain\Model\Fach();

        $view = $this->getMock(self::VIEWINTERFACE);
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method(self::ASSIGN)->with('fach', $fach);

        $this->subject->showAction($fach);
    }

    /**
     * @test
     */
    public function newActionAssignsTheGivenFachToView() {
        $newFach = new \ReRe\Rere\Domain\Model\Fach();
        $mockModul = new \ReRe\Rere\Domain\Model\Modul();

        $mockRequest = $this->getMock(self::REQUEST);
        $mockRequest->expects($this->once())->method('getArgument')->with('modul');
        $this->inject($this->subject, 'request', $mockRequest);

        $modulRepository = $this->getMock(self::MODULREPOSITORY, array('findByUid'), array(), '', FALSE);
        $modulRepository->expects($this->once())->method('findByUid')->willReturn(0);
        $this->inject($this->subject, 'modulRepository', $modulRepository);

        $view = $this->getMock(self::VIEWINTERFACE);
        $view->expects($this->once())->method('assignMultiple')->with(
                array('newFach' => $newFach, self::MODULUID => $mockModul->getUid(), 'modulname' => $mockModul->getModulname(), 'modulnummer' => $mockModul->getModulnr(), 'gueltigkeitszeitraum' => $mockModul->getGueltigkeitszeitraum()));
        $this->inject($this->subject, 'view', $view);

        $this->subject->newAction($newFach);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenFachToFachRepository() {
        $faecher = array(
            'fachname' => 'SOTE1',
            'fachnummer' => '123',
            'pruefer' => 'Johner',
            'notenschema' => 'Schulnoten',
            self::MODULUID => '1',
        );

        $mockRequest = $this->getMock(self::REQUEST);
        $mockRequest->expects($this->once())->method('hasArgument')->with(self::MODULUID);
        $mockRequest->expects($this->once())->method('getArgument')->with(self::MODULUID);
        $this->inject($this->subject, 'request', $mockRequest);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
        $objectManager->expects($this->once())->method('create')->will($this->returnValue($mockFach));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $mockFach = $this->getMock('\\ReRe\\Rere\\Domain\\Model\\Fach', array(), array(), '', FALSE);
        $mockFach->expects($this->at(0))->method('setFachname')->with('SOTE1');
        $mockFach->expects($this->at(1))->method('setFachnr')->with('123');
        $mockFach->expects($this->at(2))->method('setPruefer')->with('Johner');
        $mockFach->expects($this->at(3))->method('setNotenschema')->with('Schulnoten');
        $mockFach->expects($this->at(4))->method('setModulnr')->with('1');

        $fachRepository = $this->getMock(self::FACHREPOSITORY, array('add'), array(), '', FALSE);
        $fachRepository->expects($this->once())->method('add')->with($faecher);
        $this->inject($this->subject, self::FACHREPO, $fachRepository);

        $this->subject->createAction($faecher);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenFachToView() {
        $fach = new \ReRe\Rere\Domain\Model\Fach();

        $view = $this->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method(self::ASSIGN)->with('fach', $fach);

        $this->subject->editAction($fach);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenFachInFachRepository() {
        $fach = new \ReRe\Rere\Domain\Model\Fach();

        $fachRepository = $this->getMock(self::FACHREPOSITORY, array('update'), array(), '', FALSE);
        $fachRepository->expects($this->once())->method('update')->with($fach);
        $this->inject($this->subject, self::FACHREPO, $fachRepository);

        $this->subject->updateAction($fach);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenFachFromFachRepository() {
        $fach = new \ReRe\Rere\Domain\Model\Fach();

        $fachRepository = $this->getMock(self::FACHREPOSITORY, array('remove'), array(), '', FALSE);
        $fachRepository->expects($this->once())->method('remove')->with($fach);
        $this->inject($this->subject, self::FACHREPO, $fachRepository);

        $this->subject->deleteAction($fach);
    }

}
