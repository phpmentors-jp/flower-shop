<?php

namespace AppBundle\Controller;

use FleurMemoire\Item\Entity\Item;
use FleurMemoire\Sales\Service\Query\OrderAvailabilityQuery;
use FleurMemoire\Sales\UseCase\Command\OrderAddCommand;
use FleurMemoire\Sales\UseCase\OrderAdd;
use FleurMemoire\Stock\Service\ItemStockService;
use FleurMemoire\Stock\Service\Query\ItemStockQuery;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/item")
 */
class ItemController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $items = $this->getDoctrine()->getManager()->getRepository('Item:Item')->findAll();
        return $this->render('item/index.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/{id}")
     * @ParamConverter("item", class="Item:Item")
     */
    public function selectDateAction(Item $item)
    {
        $today = new Date('');
        $term = new Term($today, $today->addDays(20));
        $service = $this->get('fleur_memoire.sales.service.order_availability_checker');

        $availableList = [];
        foreach ($term as $date)
        {
            $query = new OrderAvailabilityQuery($item, $date, $today);
            $availableList[$date->format('Y-m-d')] = $service->run($query);
        }

        return $this->render('item/selectDate.html.twig', ['item' => $item, 'availableList' => $availableList]);
    }

    /**
     * @Route("/{id}/order/{dateStr}")
     * @ParamConverter("item", class="Item:Item")
     */
    public function orderAction(Item $item, $dateStr)
    {
        $command = new OrderAddCommand($item, new Date($dateStr), 1);

        /** @var OrderAdd $service */
        $service = $this->get('fleur_memoire.sales.use_case.order_add');
        $service->run($command);

        return $this->redirect($this->generateUrl('app_item_selectdate', ['id'=>$item->getId()]));
    }
}
