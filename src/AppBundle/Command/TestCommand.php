<?php
namespace AppBundle\Command;

use FleurMemoire\Stock\Service\Command\MaterialStockCalculateCommand;
use FleurMemoire\Stock\Service\MaterialStockCalculator;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('test:test')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var MaterialStockCalculator $service */
        $service = $container->get('fleur_memoire.stock.service.material_stock_calculator');

        $materials = $container->get('doctrine')->getManager()->getRepository('Item:Material')->findAll();

        $term = new Term(new Date(''), new Date('+7 days'));

        foreach ($materials as $material)
        {
            $command = new MaterialStockCalculateCommand($material, $term);
            $service->run($command);
        }

        $output->writeln('単品在庫予定表の完了しました');
    }
}
