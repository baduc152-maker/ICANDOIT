<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * Sinh sitemap.xml cho các trang công khai của website trung tâm.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('site.home'), 'priority' => '1.0', 'freq' => 'weekly'],
            ['loc' => route('site.about'), 'priority' => '0.8', 'freq' => 'monthly'],
            ['loc' => route('site.courses'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => route('site.teachers'), 'priority' => '0.7', 'freq' => 'monthly'],
            ['loc' => route('site.schedule'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => route('site.posts'), 'priority' => '0.7', 'freq' => 'weekly'],
            ['loc' => route('site.contact'), 'priority' => '0.8', 'freq' => 'monthly'],
        ];

        foreach (config('center.courses') as $course) {
            $urls[] = ['loc' => route('site.course', $course['slug']), 'priority' => '0.8', 'freq' => 'monthly'];
        }

        foreach (config('center.posts') as $post) {
            $urls[] = ['loc' => route('site.post', $post['slug']), 'priority' => '0.6', 'freq' => 'monthly'];
        }

        $xml = view('site.sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
