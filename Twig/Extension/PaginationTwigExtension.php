<?php

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

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('paginator', array($this, 'getPaginator'), array('is_safe' => array('html'))),
        );
    }
    
    public function getPaginator(Paginator $paginator)
    {
        return $this->renderBlock('pagination_widget', array(
            'pages' => $paginator->getPages(),
        ));
    }
    
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
     * 
     * @return \Twig_Template[]
     */
    protected function getTemplates()
    {
        return array($this->environment->loadTemplate($this->template));
    }
}