<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Shows the weather forecast for a city by country code and name',
)]
readonly class WeatherCityCommand
{
    # zawartosc pliku wygenerowana przez SI
    public function __construct(
        private WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code, e.g. PL')] string $country,
        #[Argument('City name, e.g. "Świnoujście Przytór"')] string $city,
    ): int {
        $country = strtoupper($country);
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($measurements === []) {
            $io->warning(sprintf('No forecast for %s, %s (unknown city or no future measurements).', $city, $country));

            return Command::FAILURE;
        }

        $io->writeln(sprintf('Location: %s, %s', $city, $country));
        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemprature(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
