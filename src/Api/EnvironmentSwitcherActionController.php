<?php declare(strict_types=1);

namespace JblEnvSwitcher\Api;

use JblEnvSwitcher\Api\Exception\EnvironmentSwitchActionException;
use Shopware\Core\Framework\Api\Context\AdminApiSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EnvironmentSwitcherActionController
 * @package JblEnvSwitcher\Api
 * @author Jeffry Block <hello@jeffblock.de>
 */
#[Route(defaults: ['XmlHttpRequest' => true, '_routeScope' => ['administration']])]
class EnvironmentSwitcherActionController extends AbstractController
{

    /**
     * @param RequestDataBag $request
     * @param Context $context
     * @return JsonResponse
     * @author Jeffry Block <hello@jeffblock.de>
     */
    #[Route(path: '/api/_action/switch-environment', name: 'api.action.switch-environment', methods: ['POST'])]
    public function switchEnvironment(RequestDataBag $request, Context $context): JsonResponse
    {
        try {
            /** @var bool $isAdmin */
            $isAdmin = $context->getSource() instanceof AdminApiSource && $context->getSource()->isAdmin();

            /** @var string $target */
            $target = $request->getAlnum("targetEnvironment");

            if(!$isAdmin) {
                throw new EnvironmentSwitchActionException("Not allowed");
            }

            if(!\in_array($target, ['dev', 'prod'])){
                throw new EnvironmentSwitchActionException("Invalid parameter: only dev and prod allowed");
            }

            /** @var string $dotenv */
            $dotenv = $_SERVER["DOCUMENT_ROOT"] . '/../.env';

            if(!file_exists($dotenv) || !is_writable($dotenv)){
                throw new EnvironmentSwitchActionException(".env not found or not writeable");
            }

            /** @var string $from */
            $from = $target == 'dev' ? 'prod' : 'dev';

            $envContents = file_get_contents($dotenv);
            $newEnvContents = preg_replace('/APP_ENV=' . $from . '/', 'APP_ENV=' . $target, $envContents);

            if(!$newEnvContents || $envContents === $newEnvContents) {
                throw new EnvironmentSwitchActionException("Could not find APP_ENV entry in .env");
            }

            file_put_contents($dotenv, $newEnvContents);

            return new JsonResponse([
                "success" => true,
            ], 200);

        } catch(EnvironmentSwitchActionException|\Throwable $ex) {
            return new JsonResponse([
                "success" => false,
                "message" => $ex->getMessage(),
            ], $ex->getStatusCode());
        }
    }

    #[Route(path: '/api/_action/current-environment', name: 'api.action.current-environment', methods: ['GET'])]
    public function getCurrentEnvironment(RequestDataBag $request, Context $context): JsonResponse
    {

        if(!isset($_SERVER["APP_ENV"]) || !\in_array(strtolower($_SERVER["APP_ENV"]), ["prod","dev"])){
            return new JsonResponse([
                "success" => false,
                "message" => "Could not retrieve environment information",
            ], 400);
        }

        return new JsonResponse([
            "success" => true,
            "environment" => strtolower($_SERVER["APP_ENV"])
        ], 200);
    }
}
