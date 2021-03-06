<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\ExceptionFactory;
use function call_user_func;

class GuardedInvoker
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;

    public function __construct(string $methodName, callable $callback)
    {
        $this->callback = $callback;
        $this->methodName = $methodName;
    }

    public function catch(): GuardedInvocation
    {
        $this->clearErrors();
        $result = call_user_func($this->callback);
        $exception = $this->exception($result);
        $this->clearErrors();

        return new GuardedInvocation($result, $exception);
    }

    private function clearErrors(): void
    {
        (new ErrorsCleaner())->clear();
    }

    private function exception($result): ?SafeRegexException
    {
        return (new ExceptionFactory())->retrieveGlobals($this->methodName, $result);
    }
}
