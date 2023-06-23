<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubApiController extends Controller
{
    private const API_BASE_URL = 'https://api.github.com';

    public function index(Request $request)
    {
        $repos = collect();

        // used for loop because default api get only 100 results
        for ($page = 1; $page <= 5; $page++) {
            $query = [
                'q' => 'topic:php',
                'per_page' => 20,
                'page' => $page,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.github.token'),
            ])->get(self::API_BASE_URL . '/search/repositories', $query);

            $repos = $repos->merge(collect($response->json()['items'])->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'full_name' => $item['full_name'],
                    'html_url' => $item['html_url'],
                    'language' => $item['language'],
                    'updated_at' => $item['updated_at'],
                    'pushed_at' => $item['pushed_at'],
                    'stargazers_count' => $item['stargazers_count'],
                ];
            }));
        }
        
        $name = $request->query('search');

        // search logic
        if ($name) {
            $repos = $repos->filter(function ($repo) use ($name) {
                return stripos($repo['name'], $name) !== false;
            });
        }

        // sort logic
        $repos = $repos->when($request->query('sort'), function ($collection) use ($request) {
            $sortField = $request->query('sort');
            $sortDirection = $request->query('sort_direction', 'asc');
            $callback = function ($repo) use ($sortField) {
                return strtolower($repo[$sortField]);
            };
            return $collection->sortBy($callback, SORT_REGULAR, $sortDirection === 'desc');
        });

        // pagination logic
        $perPage = $request->query('per_page', 5);
        $page = $request->query('page', 1);
        $paginatedRepos = $repos->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data' => $paginatedRepos,
            'per_page' => $perPage,
            'page' => $page,
            'total' => $repos->count(),
        ]);
    }
}