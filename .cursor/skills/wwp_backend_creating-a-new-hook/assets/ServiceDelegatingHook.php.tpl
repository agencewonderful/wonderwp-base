<?php

namespace [PluginNamespace]\Hooks;

use [PluginNamespace]\Request\[Action]Request;
use [PluginNamespace]\Service\[Action]Service;
use WonderWp\Component\Hook\Traits\HasHookManager;
use WonderWp\Component\Hook\Traits\HasHookManagerInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;
use WonderWp\Component\PluginSkeleton\ManagerAwareInterface;
use WonderWp\Component\PluginSkeleton\ManagerAwareTrait;

class [Action]Hook implements RegistrableInterface, HasHookManagerInterface, ManagerAwareInterface
{
    use HasHookManager;
    use ManagerAwareTrait;

    protected [Action]Service $[actionCamel]Service;

    public function wire(): void
    {
        $this->[actionCamel]Service = $this->manager->getService($this->manager::[ACTION]_SERVICE);
    }

    public function register(): void
    {
        $this->wire();
        $this->addFilter('[wordpress-hook-name]', [$this, 'on[Action]'], 10, /* accepted_args */);
    }

    /**
     * Service-delegating hook: map hook params → Request → Service → map Response → return.
     * No business logic here — only channel adaptation.
     */
    public function on[Action](/* hook parameters */)
    {
        $request = new [Action]Request(/* map from hook parameters */);
        $response = $this->[actionCamel]Service->handle($request);

        if (!$response->isSuccess()) {
            // For filters: return original value. For actions: return void or early.
            return /* original value or null */;
        }

        return $response->/* payloadGetter */();
    }
}
