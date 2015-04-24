<?php
$manager = $this->getContainer()->get('h4cc_alice_fixtures.manager');

$set = $manager->createFixtureSet();
$set->addFile(__DIR__.'/fixtures/Supplier.yml', 'yaml');
$set->addFile(__DIR__.'/fixtures/Customers.yml', 'yaml');
$set->addFile(__DIR__.'/fixtures/Materials.yml', 'yaml');
$set->addFile(__DIR__.'/fixtures/Items.yml', 'yaml');
//$set->addFile(__DIR__.'/fixtures/DailyStockOfMaterial.yml', 'yaml');

$set->setLocale('ja_JP');
$set->setSeed(105);
$set->setDoPersist(true);
$set->setDoDrop(true);

return $set;
