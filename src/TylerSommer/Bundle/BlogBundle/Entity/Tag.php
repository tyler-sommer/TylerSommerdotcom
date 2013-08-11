<?php

namespace TylerSommer\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A post
 *
 * @ORM\Entity(repositoryClass="TylerSommer\Bundle\BlogBundle\Entity\Repository\TagRepository")
 */
class Tag extends AbstractTag
{

}
