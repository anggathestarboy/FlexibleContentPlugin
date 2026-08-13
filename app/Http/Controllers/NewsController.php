<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function show(News $news)
    {
        abort_unless($news->exists, 404);

        $recentNews = News::query()
            ->whereKeyNot($news->getKey())
            ->latest()
            ->take(3)
            ->get();

        return view('news.show', [
            'news' => $news,
            'recentNews' => $recentNews,
        ]);
    }
}
