<?php
namespace AppBundle\Command;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Stock\Service\Command\MaterialStockCalculateCommand;
use FleurMemoire\Stock\Service\MaterialStockCalculator;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StockUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fleur:stock:update')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $container->get('fleur_memoire.stock.service.all_stock_update_service')->run();

        $output->writeln('単品在庫予定表を更新しました');
    }
}
