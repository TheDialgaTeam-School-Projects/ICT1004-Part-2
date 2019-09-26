<?php

namespace Controller;

class LogoutController extends AbstractController
{
    protected function indexAction(): array
    {
        session_destroy();
        header(sprintf('Location: %s', HOME_PAGE));
        return [];
    }
}