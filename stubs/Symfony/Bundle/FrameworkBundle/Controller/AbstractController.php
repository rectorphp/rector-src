<?php

declare(strict_types=1);

namespace Symfony\Bundle\FrameworkBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

if (class_exists('Symfony\Bundle\FrameworkBundle\Controller\AbstractController')) {
    return;
}

abstract class AbstractController
{
    final public function getDoctrine(): ManagerRegistry
    {
    }

    final public function render($templateName, $params = []): Response
    {
    }

    final public function redirectToRoute($routeName): RedirectResponse
    {
    }
}
