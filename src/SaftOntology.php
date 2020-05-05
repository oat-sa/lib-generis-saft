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

use oat\generis\model\data\Ontology;
use oat\oatbox\service\ConfigurableService;
use Saft\Store\Store;
use Saft\Addition\ARC2\Store\ARC2;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use Saft\Sparql\Query\QueryFactoryImpl;
use Saft\Sparql\Result\ResultFactoryImpl;
use Saft\Rdf\StatementIteratorFactoryImpl;
use Saft\Rdf\CommonNamespaces;
use Saft\Rdf\RdfHelpers;
use oat\GenerisSaft\rdfs\SaftRdfs;

class SaftOntology extends ConfigurableService implements Ontology
{
    /** @var Store */
    private $store;

    function getResource($uri)
    {
        $resource = new \core_kernel_classes_Resource($uri);
        $resource->setModel($this);
        return $resource;
    }
    
    function getClass($uri)
    {
        $class = new \core_kernel_classes_Class($uri);
        $class->setModel($this);
        return $class;
    }
    
    function getProperty($uri)
    {
        $property = new \core_kernel_classes_Property($uri);
        $property->setModel($this);
        return $property;
    }

    public function getRdfsInterface()
    {
        return new SaftRdfs($this->getStore());
    }

    public function getRdfInterface()
    {
        return new SaftRdf($this->getStore());
    }

    public function getSearchInterface()
    {
    }
    
    public function getStore(): Store
    {
        $rdfHelpers = new RdfHelpers();
        if (is_null($this->store)) {
            $this->store = new ARC2(
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
        }
        return $this->store;
    }

}
