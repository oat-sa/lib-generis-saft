<?php

//use \ARC2 as BaseArc;
use Saft\Addition\ARC2\Store\ARC2;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use Saft\Sparql\Query\QueryFactoryImpl;
use Saft\Rdf\StatementIteratorFactoryImpl;
use Saft\Sparql\Result\ResultFactoryImpl;
use Saft\Rdf\CommonNamespaces;
use Saft\Rdf\RdfHelpers;

// test only

require_once __DIR__.'/../../../../vendor/autoload.php';
//require_once dirname(__FILE__) . '/../includes/raw_start.php';

$rdfHelpers = new RdfHelpers();

$store = new ARC2(
    new NodeFactoryImpl(),
    new StatementFactoryImpl(),
    new QueryFactoryImpl($rdfHelpers),
    new ResultFactoryImpl(),
    new StatementIteratorFactoryImpl(),
    $rdfHelpers,
    new CommonNamespaces(),
    [
        'database' => 'tao35',
        'username' => 'tao',
        'password'  => 'gotao',
    ]
);

$nf = new NodeFactoryImpl();
$sf = new StatementFactoryImpl();
$graph = $nf->createNamedNode("http://example.org/");
$s = $nf->createNamedNode("http://example.org/s");
$p = $nf->createNamedNode("http://example.org/p");
$o = $nf->createNamedNode("http://example.org/x");
$any = $nf->createAnyPattern();

$statement = $sf->createStatement($s, $p, $o, $graph);
$store->addStatements([$statement]);

// Fetch Data
$pattern = $sf->createStatement($s, $p, $any, $graph);
$statements = $store->getMatchingStatements($pattern);

foreach ($statements as $select) {
    var_dump($select);
}


