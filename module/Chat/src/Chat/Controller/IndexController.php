<?php
/**
 * Created by PhpStorm.
 * User: alain
 * Date: 6/17/15
 * Time: 9:10 AM
 */
namespace Chat\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/custom');
        return new ViewModel();
    }
}