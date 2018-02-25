<?php

namespace Injector\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class NotMappedException extends RuntimeException implements ContainerExceptionInterface
{

}
