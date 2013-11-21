<?php

namespace Neobazaar\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Zend\Debug\Debug;

class StaticController 
	extends AbstractActionController
{
	public function classifiedAction() 
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal($this->getRequest()->isXmlHttpRequest());
		
// 		$thumbnailer = $this->getServiceLocator()->get('Thumbnailer');
// 		$thumbnailer->open('C:\Users\Sergio\workspace\neobazaar\vendor\razor\thumbnailer\test\data\gatti.jpg');
// 		$thumbnailer->resize(400);
// 		$thumbnailer->save('C:\Users\Sergio\workspace\neobazaar\vendor\razor\thumbnailer\test\data\gatti1.jpg'); 
		
// 		$watermarker = $this->getServiceLocator()->get('Watermarker');
// 		$watermarker->openImage('C:\Users\Sergio\workspace\neobazaar\module\Watermarker\test\data\Berserk.jpg');
// 		$watermarker->openWatermark('C:\Users\Sergio\workspace\neobazaar\module\Watermarker\test\data\watermark.gif');
// 		$watermarker->watermark();

		$cropper = $this->getServiceLocator()->get('Cropper');
		$cropper->open('C:\Users\Sergio\workspace\neobazaar\module\Cropper\test\data\gatti.jpg');
		
		// Type of the cut, on top image (2) or centered (1), default 1
		// $cropper->setType(1);
		
		// this will cut a 200x200 square
		//$cropper->setSize(200, 200);
		
		// this will cut a square in the source image, where side equal source image width
		$cropper->setSquareMode(true); 
		
		$cropper->save('C:\Users\Sergio\workspace\neobazaar\module\Cropper\test\data\gatti1.jpg');
		
		exit;
		
		return $viewModel;
	}
}