<?php

namespace App\Service;

class OfferSuggestionService
{
    public const LANDING_PAGE = 'Landing Page Express';
    public const SITE_VITRINE = 'Site Vitrine Essentiel';
    public const SITE_ASSOCIATION = 'Site Association / Structuré';
    public const APP_SYMFONY = 'Application Web Symfony';

    public function suggest(array $data): string
    {
        $pages = $data['estimatedPages'] ?? '';
        $objective = $data['mainObjective'] ?? '';
        $autonomy = $data['needsAutonomy'] ?? 'no';
        $budget = $data['budget'] ?? '';
        $features = $data['features'] ?? [];

        $hasComplexFeatures = !empty(array_intersect($features, [
            'connexion_espace_membre',
            'gestion_donnees',
        ]));

        // Application Symfony — complex needs
        if ($objective === 'automatiser' || $hasComplexFeatures) {
            return self::APP_SYMFONY;
        }

        // Landing Page Express
        if (
            $pages === '1_page'
            && in_array($objective, ['visible', 'tester'])
            && $autonomy !== 'oui'
            && in_array($budget, ['moins_200', 'aucune_idee'])
        ) {
            return self::LANDING_PAGE;
        }

        // Site Association
        if (
            in_array($pages, ['6_10', 'plus_10'])
            && in_array($objective, ['informer', 'adhesions'])
        ) {
            return self::SITE_ASSOCIATION;
        }

        // Site Vitrine Essentiel
        if (
            in_array($pages, ['1_page', '2_5'])
            && in_array($objective, ['visible', 'informer'])
            && !$hasComplexFeatures
        ) {
            return self::SITE_VITRINE;
        }

        // Scoring fallback
        return $this->scoreFallback($pages, $objective, $budget, $features);
    }

    private function scoreFallback(string $pages, string $objective, string $budget, array $features): string
    {
        $scores = [
            self::LANDING_PAGE => 0,
            self::SITE_VITRINE => 0,
            self::SITE_ASSOCIATION => 0,
            self::APP_SYMFONY => 0,
        ];

        // Pages scoring
        match ($pages) {
            '1_page' => $scores[self::LANDING_PAGE] += 3,
            '2_5' => $scores[self::SITE_VITRINE] += 3,
            '6_10' => $scores[self::SITE_ASSOCIATION] += 3,
            'plus_10' => $scores[self::SITE_ASSOCIATION] += 2,
            default => null,
        };

        // Budget scoring
        match ($budget) {
            'moins_200' => $scores[self::LANDING_PAGE] += 2,
            '200_600' => $scores[self::SITE_VITRINE] += 2,
            '600_1500' => $scores[self::SITE_ASSOCIATION] += 2,
            '1500_3000', 'plus_3000' => $scores[self::APP_SYMFONY] += 2,
            default => null,
        };

        // Features scoring
        foreach ($features as $feature) {
            if (in_array($feature, ['actualites', 'adhesion'])) {
                $scores[self::SITE_ASSOCIATION] += 1;
            }
            if (in_array($feature, ['connexion_espace_membre', 'gestion_donnees'])) {
                $scores[self::APP_SYMFONY] += 2;
            }
        }

        arsort($scores);

        return array_key_first($scores);
    }
}
