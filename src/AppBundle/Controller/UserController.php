<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.01.17
 * Time: 18:11
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('auth/index.html.twig');
    }
}