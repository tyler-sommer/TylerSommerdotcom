<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

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
