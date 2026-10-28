<?php

namespace [PluginNamespace]\Response;

use WonderWp\Component\Response\AbstractResponse;

class [Action]Response extends AbstractResponse
{
    public function __construct(
        // private readonly mixed $payload = null,
        int $code = 200,
        string $msgKey = '[action-snug].success',
        string $textDomain = '[TextDomain]'
    ) {
        parent::__construct($code, $msgKey, $textDomain);
    }

    // Add payload getters only
}
