<?php declare(strict_types=1);

namespace JblEnvSwitcher\Api\Exception;

use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EnvironmentSwitchActionException
 * @package JblEnvSwitcher\Api\Exception
 * @author Jeffry Block <hello@jeffblock.de>
 */
class EnvironmentSwitchActionException extends ShopwareHttpException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * @return string
     * @author Jeffry Block <hello@jeffblock.de>
     */
    public function getErrorCode(): string
    {
        return 'ENVIRONMENT_SWITCH_ERROR';
    }

    /**
     * @return int
     * @author Jeffry Block <hello@jeffblock.de>
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
