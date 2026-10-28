// Add to [Plugin]Manager.php

const [ACTION]_SERVICE = '[Action]Service';

// Inside register(Container $container) or equivalent:

$this->addService(self::[ACTION]_SERVICE, function () {
    return new [Action]Service(
        // $this->getService(ServiceInterface::REPOSITORY_SERVICE_NAME),
    );
});
