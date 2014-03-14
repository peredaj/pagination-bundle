<?php

/*
 * This file is part of the PeredajPaginationBundle package.
 *
 * (c) Bochkarev Konstantin <konstantin.bochkarev@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peredaj\PaginationBundle\Twig\Extension;


use Peredaj\PaginationBundle\Paginator\Paginator;

class PaginationTwigExtension extends \Twig_Extension
{
    /**
     * Template
     *
     * @var \Twig_TemplateInterface
     */
    private $template;
    
    /**
     *
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * Constructor
     * 
     * @param string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'peredaj.paginator.twig_extension';
    }
    
    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('pagination', array($this, 'getPagination'), array('is_safe' => array('html'))),
        );
    }
    
    /**
     * Pagination twig function
     * 
     * @param \Peredaj\PaginationBundle\Paginator\Paginator $paginator
     * 
     * @return string
     */
    public function getPagination(Paginator $paginator)
    {
        return $this->renderBlock('pagination_widget', array(
            'pages' => $paginator->getPages(),
        ));
    }
    
    /**
     * Pagination block rendering
     * 
     * @param string $name
     * @param array $parameters
     * 
     * @return string
     * 
     * @throws \InvalidArgumentException
     */
    public function renderBlock($name, $parameters)
    {
        foreach($this->getTemplates() as $template)
        {
            if($template->hasBlock($name))
            {
                return $template->renderBlock($name, array_merge($this->environment->getGlobals(), $parameters));
            }
        }
        
        throw new \InvalidArgumentException(sprintf('Block "%s" not found', $name));
    }
    
    /**
     * Get template objects
     * 
     * @return \Twig_Template[]
     */
    protected function getTemplates()
    {
        return array($this->environment->loadTemplate($this->template));
    }
}