<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $name,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        $rows = array_map(fn(Measurement $m) => [
            'city' => $m->getLocation()->getName(),
            'country' => $m->getLocation()->getCountry(),
            'date' => $m->getDate()->format('Y-m-d'),
            'celsius' => (float) $m->getTemprature(),
            'fahrenheit' => $m->getFahrenheit(),
        ],$this->weatherUtil->getWeatherForCountryAndCity($country, $name));

        if ($twig) {
            # wygenerowane przez AI
            $isCsv = $format === 'csv';

            return $this->render(
                $isCsv ? 'weather_api/index.csv.twig' : 'weather_api/index.json.twig',
                ['name' => $name, 'country' => $country, 'rows' => $rows],
                new Response(headers: ['Content-Type' => $isCsv ? 'text/csv' : 'application/json']),
            );
        }

        if ($format === 'csv') {
            # wygenerowane przez AI
            $csv = "city,country,date,celsius,fahrenheit\n";
            foreach ($rows as $row) {
                $csv .= implode(',', $row) . "\n";
            }
            return new Response($csv, 200, ['Content-Type' => 'text/csv']);
        }

        return $this->json(['name' => $name, 'country' => $country, 'measurements' => $rows]);
    }
}
