<?php

namespace AppBundle\Controller;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/stock")
 */
class StockController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $materials = $this->getDoctrine()->getManager()->getRepository('Item:Material')->findAll();
        return $this->render('stock/index.html.twig', ['materials' => $materials]);
    }

    /**
     * @Route("/material/{id}")
     * @ParamConverter("material", class="Item:Material")
     */
    public function dailyStockListAction(Material $material)
    {
        $days = max(20, $material->getStockCalcDays());
        $term = new Term(new Date(''), new Date(sprintf('+%s days', $days)));
        $stocks = $this->getDoctrine()->getManager()->getRepository('Stock:DailyStockOfMaterial')
            ->findByMaterialAndTerm($material, $term);

        return $this->render('stock/material.html.twig', ['term' => $term, 'material' => $material, 'stocks' => $stocks]);
    }
}
