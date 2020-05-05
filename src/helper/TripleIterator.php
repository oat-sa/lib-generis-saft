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
use Iterator;

class TripleIterator implements Iterator
{
    private $internal;

    public function __construct(Iterator $statementIterator)
    {
        $this->internal = $statementIterator;
    }

    public function next()
    {
        return $this->internal->next();
    }

    public function valid()
    {
        return $this->internal->valid();
    }

    /**
     * {@inheritDoc}
     * @see Iterator::current()
     * @return \core_kernel_classes_Triple
     */
    public function current()
    {
        $helper = new TripleHelper();
        return $helper->statementToTriple($this->internal->current());
    }

    public function rewind()
    {
        return $this->internal->rewind();
    }

    public function key()
    {
        return $this->internal->key();
    }

}
