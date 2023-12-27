<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MakerBundle\MakerBundle::class => [
        'dev' => true,
    ],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\TwigBundle\TwigBundle::class => [
        'all' => true,
    ],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Symfonycasts\TailwindBundle\SymfonycastsTailwindBundle::class => [
        'all' => true,
    ],
    TalesFromADev\Twig\Extra\Tailwind\Bridge\Symfony\Bundle\TalesFromADevTwigExtraTailwindBundle::class => [
        'all' => true,
    ],
    TalesFromADev\FlowbiteBundle\FlowbiteBundle::class => [
        'all' => true,
    ],
    Symfony\UX\StimulusBundle\StimulusBundle::class => [
        'all' => true,
    ],
    Symfony\UX\Turbo\TurboBundle::class => [
        'all' => true,
    ],
    Knp\Bundle\TimeBundle\KnpTimeBundle::class => [
        'all' => true,
    ],
    BabDev\PagerfantaBundle\BabDevPagerfantaBundle::class => [
        'all' => true,
    ],
    Symfony\UX\TwigComponent\TwigComponentBundle::class => [
        'all' => true,
    ],
    Symfony\UX\LiveComponent\LiveComponentBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MercureBundle\MercureBundle::class => [
        'all' => true,
    Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Hautelook\AliceBundle\HautelookAliceBundle::class => [
        'dev' => true,
        'test' => true,
    ],
];
