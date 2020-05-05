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

namespace oat\GenerisSaft\helper;

use core_kernel_classes_Triple;
use Saft\Rdf\Statement;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;

class TripleHelper
{
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
    
    protected function statementToTriple(Statement $statement): core_kernel_classes_Triple
    {
        return core_kernel_classes_Triple::createTriple(
            $statement->getGraph()->getUri(),
            $statement->getSubject()->getUri(),
            $statement->getPredicate()->getUri(),
            $statement->getObject()->getUri(),
        );
    }
}
