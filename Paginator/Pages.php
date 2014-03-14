<?php

/*
 * This file is part of the PeredajPaginationBundle package.
 *
 * (c) Bochkarev Konstantin <konstantin.bochkarev@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peredaj\PaginationBundle\Paginator;

use Doctrine\ORM\Query;
use Peredaj\PaginationBundle\Paginator\Paginator;


class Pages implements \Iterator, \Countable
{
    /**
     * Paginator
     *
     * @var Peredaj\PaginationBundle\Paginator\Paginator
     */
    private $paginator;
    
    /**
     * Range size
     *
     * @var integer
     */
    private $rangeSize;
    
    /**
     * Minimum link range
     *
     * @var integer
     */
    private $minRange;
    
    /**
     * Maximum link range
     *
     * @var integer
     */
    private $maxRange;

    /**
     * Page count
     *
     * @var integer
     */
    private $count;
    
    /**
     * Page counter
     *
     * @var integer
     */
    private $position;
    
    /**
     * Current page number
     *
     * @var integer
     */
    private $current;
    
    /**
     * Constructor
     * 
     * @param \Doctrine\ORM\Query $query
     */
    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
        $this->setRangeSize(5);
        $this->rewind();
    }
    
    /**
     * Set range size
     * 
     * @param integer $rangeSize
     * @return \Peredaj\PaginationBundle\Paginator\Pages
     */
    public function setRangeSize($rangeSize)
    {
        if($rangeSize < 3)
        {
            $rangeSize = 3;
        }
        
        if(($rangeSize % 2) == 0)
        {
            $rangeSize += 1;
        }
        
        $this->rangeSize = $rangeSize;
        $this->minRange = $this->getCurrent() - (($this->rangeSize - 1) / 2);        
        $this->maxRange = $this->getCurrent() + (($this->rangeSize - 1) / 2);
        
        return $this;
    }
    
    /**
     * Get paginator
     * 
     * @return Peredaj\PaginationBundle\Paginator\Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }
    
    /**
     * Get minimum range number
     * 
     * @return integer
     */
    public function getMinRange()
    {
        return $this->minRange;
    }
    
    /**
     * Get maximum range number
     * 
     * @return integer
     */
    public function getMaxRange()
    {
        return $this->maxRange;
    }
    
    /**
     * Get range size
     * 
     * @return integer
     */
    public function getRangeSize()
    {
        return $this->rangeSize;
    }
    
    /**
     * Get curren page number
     * 
     * @return integer
     */
    public function getCurrent()
    {
        if(null === $this->current)
        {
            $this->current = $this->getPaginator()->getQuery()->getFirstResult() == 0 
                ? 1
                : ($this->getPaginator()->getQuery()->getFirstResult() / $this->getPaginator()->getQuery()->getMaxResults());
        }
        
        return $this->current;
    }
    
    /**
     * Get count pages
     * 
     * @return integer 
     */
    public function count()
    {
        if(null === $this->count)
        {
             $this->count = ceil($this->getPaginator()->count() / $this->getPaginator()->getQuery()->getMaxResults()) - 1;
        }
        
        return $this->count;
    }   
    
    /**
     * Get page is current flag
     * 
     * @return boolean
     */
    public function isCurrent()
    {
        return $this->getNumber() == $this->getCurrent();
    }    
    
    /**
     * Get page is first flag
     * 
     * @return boolean
     */
    public function isFirst()
    {
        return $this->getNumber() == 1;
    } 
    
    /**
     * Get page is last flag
     * 
     * @return boolean
     */
    public function isLast()
    {
        return $this->getNumber() == $this->count();
    }    
    
    /**
     * Get page in link range flag
     * 
     * @return boolean
     */
    public function inRange()
    {
        return $this->getNumber() >= $this->minRange && $this->getNumber() <= $this->maxRange;
    }
    
    /**
     * Get page number
     * 
     * @return type
     */
    public function getNumber()
    {
        return $this->position + 1;
    }
    
    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }
    
    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->position;
    }
    
    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->position++;
    }
    
    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return $this->getNumber() <= $this->count();
    }
}