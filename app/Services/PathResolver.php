<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Geo\City;
use App\Models\Geo\Province;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PathResolver
{
    public function extractPageFromPath(string $path): array
    {
        // Remove 'api/' prefix if present
        $path = preg_replace('/^api\//', '', $path);

        // Handle /page/1-2-3 format
        if (preg_match('/\/page\/(\d+)$/', $path, $matches)) {
            $page = (int) $matches[1];
            $cleanPath = preg_replace('/\/page\/\d+$/', '', $path);

            return [trim($cleanPath, '/'), $page];
        }

        return [trim($path, '/'), 1];
    }

    public function resolvePath(string $path): array
    {
        if (empty($path)) {
            return [
                'filters' => [],
                'metadata' => $this->buildMetadata([]),
            ];
        }

        $segments = explode('/', $path);
        $resolvedModels = [];
        $filters = [];

        try {
            $this->resolveSegments($segments, $resolvedModels, $filters);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("Invalid route: {$path}");
        }

        return [
            'filters' => ['filters' => $filters],
            'metadata' => $this->buildMetadata($resolvedModels),
        ];
    }

    protected function resolveSegments(array $segments, array &$models, array &$filters): void
    {
        if (empty($segments)) {
            return;
        }

        // First segment: always try as Province
        $firstSegment = array_shift($segments);
        $province = Province::where('slug', $firstSegment)->firstOrFail();

        $models['province'] = $province;
        $filters['province.slug'] = $province->slug;

        if (empty($segments)) {
            return;
        }

        // Second segment: could be City or Category
        $secondSegment = array_shift($segments);

        // Try as Category first (escort, massage, etc.)
        $category = Category::where('slug', $secondSegment)->first();
        if ($category) {
            $models['category'] = $category;
            $filters['category.slug'] = $category->slug;

            // Third segment could be Tag
            if (! empty($segments)) {
                $thirdSegment = array_shift($segments);
                $tag = Tag::where('slug', $thirdSegment)->first();
                if ($tag) {
                    $models['tag'] = $tag;
                    $filters['tags.slug'] = $tag->slug;
                }
            }

            return;
        }

        // Try as City
        $city = City::where('slug', $secondSegment)
            ->where('province_id', $province->id)
            ->first();

        if ($city) {
            $models['city'] = $city;
            $filters['city.slug'] = $city->slug;

            // Third segment could be Category
            if (! empty($segments)) {
                $thirdSegment = array_shift($segments);
                $category = Category::where('slug', $thirdSegment)->firstOrFail();
                $models['category'] = $category;
                $filters['category.slug'] = $category->slug;

                // Fourth segment could be Tag
                if (! empty($segments)) {
                    $fourthSegment = array_shift($segments);
                    $tag = Tag::where('slug', $fourthSegment)->firstOrFail();
                    $models['tag'] = $tag;
                    $filters['tags.slug'] = $tag->slug;
                }
            }

            return;
        }

        // If we get here, the segment doesn't match anything
        throw new ModelNotFoundException("Invalid segment: {$secondSegment}");
    }

    protected function buildMetadata(array $models): array
    {
        $breadcrumbs = [['name' => 'Home', 'url' => '/']];
        $currentPath = '';

        // Build breadcrumbs in the correct order
        $order = ['province', 'city', 'category', 'tag'];

        foreach ($order as $type) {
            if (isset($models[$type])) {
                $model = $models[$type];
                $currentPath .= '/'.$model->slug;

                $name = match ($type) {
                    'province' => $model->name,
                    'city' => $model->name,
                    'category' => $model->title,
                    'tag' => $model->name,
                };

                $breadcrumbs[] = [
                    'name' => $name,
                    'url' => $currentPath,
                ];
            }
        }

        // Build dynamic title based on rules
        $title = $this->buildDynamicTitle($models);

        return [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
            'models' => $models,
        ];
    }

    protected function buildDynamicTitle(array $models): string
    {
        // Category label mapping
        $categoryLabels = [
            'escort' => 'Donna cerca Uomo',
            'trans' => 'Trans',
            'massaggi' => 'Massaggi',
            'massage' => 'Massaggi',
        ];

        $hasCategory = isset($models['category']);
        $hasCity = isset($models['city']);
        $hasProvince = isset($models['province']);
        $hasTag = isset($models['tag']);

        // Case 1: No category selected (Title Base)
        if (! $hasCategory) {
            if ($hasCity) {
                $location = $models['city']->name;
                // Check if city slug has 'comune-' prefix
                $isComune = str_starts_with($models['city']->slug, 'comune-');

                return $isComune ? "Escort {$location} comune" : "Escort {$location}";
            }

            if ($hasProvince) {
                return "Escort {$models['province']->name}";
            }

            return 'Escort';
        }

        // Case 2: Category selected (Title Category)
        $category = $models['category'];
        $categorySlug = $category->slug;
        $categoryLabel = $categoryLabels[$categorySlug] ?? $category->title;

        // Add tag to title if present
        if ($hasTag) {
            $tagName = $models['tag']->name;
            $categoryLabel = "{$tagName} {$categoryLabel}";
        }

        if ($hasCity) {
            $location = $models['city']->name;
            // Check if city slug has 'comune-' prefix
            $isComune = str_starts_with($models['city']->slug, 'comune-');

            return $isComune ? "{$categoryLabel} {$location} comune" : "{$categoryLabel} {$location}";
        }

        if ($hasProvince) {
            return "{$categoryLabel} {$models['province']->name}";
        }

        return $categoryLabel;
    }
}
