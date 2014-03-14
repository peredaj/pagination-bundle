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

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\ORM\Query;
use Peredaj\PaginationBundle\Paginator\Pages;

/**
 * Пагинатор с встроенным пейджером.
 * 
 * Предназначен для вывода пагинации.
 * <first><...><prev><prev><curent><next><next><...><last>
 * 
 */
class Paginator extends DoctrinePaginator
{
    /**
     * Page iterator
     *
     * @var array
     */
    private $pages;

    /**
     * Размер отображаемого диапазона страниц
     *
     * @var integer
     */
    private $rangeSize;
    
    /**
     * Номер текущей страницы
     *
     * @var integer
     */
    private $currentPageNumber;
    
    /**
     * Общее количество страниц
     *
     * @var integer
     */
    private $totalPageCount;
    
    /**
     * Нижняя граница диапазона выводимых страниц
     *
     * @var integer
     */
    private $minRange;
    
    /**
     * Верхняя граница диапазона выводимых страниц
     *
     * @var integer
     */
    private $maxRange;

    /**
     * Constructor
     * 
     * @param Doctrine\ORM\Query $query
     * @param boolean $fetchJoinCollection
     */
    public function __construct(Query $query, $fetchJoinCollection = true)
    {
        parent::__construct($query, $fetchJoinCollection);
        $this->pages = new Pages($this);
    }
    
    /**
     * Set the size range of pages
     * 
     * @param integer $size
     */
    public function setRangeOfPageSize($size)
    {
        $this->pages->setRangeSize($size);
    }
    
    /**
     * Get pages
     * 
     * @return Peredaj\PaginationBundle\Paginator\Pages
     */
    public function getPages()
    {
        return $this->pages;
    }
}