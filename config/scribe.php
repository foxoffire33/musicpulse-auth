<?php

use Knuckles\Scribe\Extracting\Strategies;

return [
    'theme' => 'default',
    'title' => 'DXMusic Authenticatie API',
    'description' => '',
    'base_url' => "http://localhost/authenticate/",
    'routes' => [
        [
            'match' => [
                'prefixes' => ['*'],
                'domains' => ['*'],
                'versions' => ['v1'],
            ],
            'include' => [],
            'exclude' => [],
            'apply' => [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'response_calls' => [
                    'methods' => ['GET'],
                    'config' => [
                        'app.env' => 'documentation',
                        'app.debug' => false,
                    ],
                    'queryParams' => [ ],
                    'bodyParams' => [],
                    'fileParams' => [ ],
                    'cookies' => [],
                ],
            ],
        ],
    ],
    'type' => 'static',
    'static' => [
        'output_path' => 'public/docs',
    ],
    'laravel' => [
        'add_routes' => true,
        'docs_url' => '/docs',
        'middleware' => [],
    ],

    'try_it_out' => [
        'enabled' => false,
        'base_url' => null,
        'use_csrf' => false,
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    'auth' => [
        'enabled' => true,
        'default' => false,
        'in' => 'bearer',
        'name' => 'token',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{TOKEN}',
        'extra_info' => 'Als u zich aanmeld krijgt u een Json Web Token. als u account heeft neem dan contact op met ons',
    ],
    'intro_text' => <<<INTRO
This documentation aims to provide all the information you need to work with our API.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
INTRO
    ,

    'example_languages' => [
        'bash',
        'php',
        'javascript',
        'python'
    ],
    'postman' => [
        'enabled' => true,
        'overrides' => [ ],
    ],

    'openapi' => [
        'enabled' => true,
        'overrides' => [],
    ],

    'default_group' => 'Endpoints',
    'logo' => false,
    'faker_seed' => null,
    'strategies' => [
        'metadata' => [
            Strategies\Metadata\GetFromDocBlocks::class,
        ],
        'urlParameters' => [
            Strategies\UrlParameters\GetFromLaravelAPI::class,
            Strategies\UrlParameters\GetFromLumenAPI::class,
            Strategies\UrlParameters\GetFromUrlParamTag::class,
        ],
        'queryParameters' => [
            Strategies\QueryParameters\GetFromFormRequest::class,
            Strategies\QueryParameters\GetFromInlineValidator::class,
            Strategies\QueryParameters\GetFromQueryParamTag::class,
        ],
        'headers' => [
            Strategies\Headers\GetFromRouteRules::class,
            Strategies\Headers\GetFromHeaderTag::class,
        ],
        'bodyParameters' => [
            Strategies\BodyParameters\GetFromFormRequest::class,
            Strategies\BodyParameters\GetFromInlineValidator::class,
            Strategies\BodyParameters\GetFromBodyParamTag::class,
        ],
        'responses' => [
            Strategies\Responses\UseTransformerTags::class,
            Strategies\Responses\UseApiResourceTags::class,
            Strategies\Responses\UseResponseTag::class,
            Strategies\Responses\UseResponseFileTag::class,
            Strategies\Responses\ResponseCalls::class,
        ],
        'responseFields' => [
            Strategies\ResponseFields\GetFromResponseFieldTag::class,
        ],
    ],

    'fractal' => [
        'serializer' => null,
    ],

    'routeMatcher' => \Knuckles\Scribe\Matching\RouteMatcher::class,

    /**
     * For response calls, API resource responses and transformer responses,
     * Scribe will try to start database transactions, so no changes are persisted to your database.
     * Tell Scribe which connections should be transacted here.
     * If you only use one db connection, you can leave this as is.
     */
    'database_connections_to_transact' => [config('database.default')]
];
