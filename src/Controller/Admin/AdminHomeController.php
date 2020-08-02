<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminHomeController extends AdminBaseController
{
    /**
     * @Route("/admin", name="admin_home")
     * @return Response
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        return $this->render('admin/index.html.twig', $forRender);
    }
}
