<?php

namespace [PluginNamespace]\Service;

use [PluginNamespace]\Request\[Action]Request;
use [PluginNamespace]\Response\[Action]Response;

class [Action]Service
{
    public function __construct(
        // Inject dependencies (repositories, HTTP clients, etc.).
    ) {
    }

    public function handle([Action]Request $request): [Action]Response
    {
        // Implement business logic here.
        // Return success or failure responses using AbstractResponse conventions.

        return new [Action]Response(
            code: 200,
            msgKey: '[action-snug].success',
            textDomain: '[TextDomain]'
        );
    }
}
