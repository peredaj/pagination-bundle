PeredajPaginationBundle
=======================
This bundle support pagination for Symfony2.
Usage:
in repository
``` php
<?php
// Repository class

use Doctrine\ORM\EntityRepository;
use Peredaj\PaginationBundle\Paginator\Paginator;

class SomeRepository extends EntityRepository
{
    ...
    
    public function getSomeResult($limit = 10, $offset = 0)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->useResultCache(false);
        
        return new Paginator($query);
    }
    
    ...
    
}
```
in controller:
``` php
<?php 
// SomeController

class SomeController
{
    ...
    
    public function AnyAction()
    {
        $entities = $this->getDoctrine()->getRepository('MyAnyBundle:SomeEntity')
            ->findTest($limit, $offset);
        
        return array(
            'entities' => $entities,
        );
    }
    
    ...
}        
```
in view:

``` twig
...

<ul>
{% for entity in entities %}
    <li>id: {{ entity.id }}</li>
{% endfor %}
</ul>
{{ paginator(entities) }}

...
```

Templating
----------
For use custom temlate:
in config.yml
``` yaml
peredaj_pagination:
    template:       MyAnyBundle::layout.html.twig
```