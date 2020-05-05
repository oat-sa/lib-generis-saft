<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 */

declare(strict_types=1);

namespace oat\GenerisSaft;

use oat\generis\model\data\RdfInterface;
use core_kernel_classes_Triple;
use Saft\Rdf\Statement;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use Saft\Store\Store;
use oat\GenerisSaft\helper\TripleIterator;

/**
 * Implementation of the RDF interface for the smooth sql driver
 *
 * @author joel bout <joel@taotesting.com>
 * @package generis
 */
class SaftRdf implements RdfInterface
{
    const BATCH_SIZE = 100;
    /**
     * @var Store
     */
    private $store;
    
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * (non-PHPdoc)
     * @see \oat\generis\model\data\RdfInterface::add()
     */
    public function add(core_kernel_classes_Triple $triple)
    {
        $this->addTripleCollection([$triple]);
    }

    /**
     * @inheritDoc
     */
    public function addTripleCollection(iterable $triples)
    {
        $triplesToInsert = [];

        foreach ($triples as $triple) {
            $triplesToInsert [] = $triple;

            if (count($triplesToInsert) >= self::BATCH_SIZE) {
                $this->insertTriples($triplesToInsert);
                $triplesToInsert = [];
            }
        }

        if (!empty($triplesToInsert)) {
            $this->insertTriples($triplesToInsert);
        }
    }

    /**
     * @param \core_kernel_classes_Triple[] $triples
     */
    protected function insertTriples(array $triples): void
    {
        $statements = array_map([$this,"tripleToStatement"], $triples);
        $this->store->addStatements($statements);
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\generis\model\data\RdfInterface::remove()
     */
    public function remove(core_kernel_classes_Triple $triple)
    {
        $this->store->deleteMatchingStatements([$this->tripleToStatement($triple)]);
    }
    
    public function getIterator()
    {
        // @TODO wrap it back to triples 
        return new TripleIterator(new \ArrayIterator($this->store->getGraphs()));
    }

    /**
     * @param core_kernel_classes_Triple $triple
     * @param array $valuesToInsert
     * @return array
     */
    protected function tripleToStatement(core_kernel_classes_Triple $triple): Statement
    {
        $nf = new NodeFactoryImpl();
        $sf = new StatementFactoryImpl();
        $graph = $nf->createNamedNode("http://example.org/".$triple->modelid);
        $s = $nf->createNamedNode($triple->subject);
        $p = $nf->createNamedNode($triple->predicate);
        $o = $nf->createNamedNode($triple->object);
        
        return $sf->createStatement($s, $p, $o, $graph);
    }
}
