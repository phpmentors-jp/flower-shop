<?php

namespace AppBundle\Controller;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Purchase\UseCase\Command\PurchaseAddCommand;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/purchase")
 */
class PurchaseController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
    }

    /**
     * @Route("/material/{id}/{dateStr}")
     * @ParamConverter("material", class="Item:Material")
     */
    public function purchaseAction(Material $material, $dateStr)
    {
        $command = new PurchaseAddCommand($material, new Date($dateStr), 10);
        $service = $this->get('fleur_memoire.purchase.use_case.purchase_add');
        $service->run($command);

        return $this->redirect($this->generateUrl('app_stock_dailystocklist', ['id'=>$material->getId()]));
    }
}
