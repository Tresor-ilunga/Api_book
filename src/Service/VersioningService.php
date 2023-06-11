<?php

declare(strict_types=1);

namespace App\Service;

use AllowDynamicProperties;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class VersioningService
 *
 *
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
#[AllowDynamicProperties] class VersioningService
{
    private $requestStack;

    /**
     * Constructeur permettant de récupérer la requête courante (pour extraire la version "Accept" du header)
     * ainsi que le ParameterBag pour récupérer la version par défaut dans le fichier de configuration.
     *
     * @param RequestStack $requestStack
     * @param ParameterBagInterface $params
     */
    public function __construct(RequestStack $requestStack, ParameterBagInterface $params)
    {
        $this->requestStack = $requestStack;
        $this->defaultVersion = $params->get('default_api_version');
    }

    /**
     * This method returns the version of the API to use.
     *
     * @return string
     */
    public function getVersion(): string
    {
        $version = $this->defaultVersion;

        $request = $this->requestStack->getCurrentRequest();
        $accept = $request->headers->get('Accept');
        // Récupération du numéro de version dans le header "Accept"
        // exemple "application/json; test=bidule; version=1.0" => $version = "1.0"
        $entete = explode(";", $accept);

        // On parcourt les éléments de l'entête pour trouver la version
        foreach ($entete as $value)
        {
            if (str_contains($value, 'version'))
            {
                $version = explode('=', $value);
                $version = $version[1];
                break;
            }
        }
        return $version;
    }
}