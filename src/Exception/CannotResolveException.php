<?php

namespace Injector\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class CannotResolveException extends RuntimeException implements ContainerExceptionInterface
{

}
