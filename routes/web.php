<?php

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Rp76\Guzzle\Client;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/', function (Request $request) {
    $request->validate([
        'vocabulary' => ['required', 'string']
    ]);

    $vocabularies = explode(PHP_EOL, str_replace("\r", '', $request->input('vocabulary')));

    $words = Word::whereIn(Word::WORD, $vocabularies)->get();

    $notFounds = array_diff($vocabularies, $words->pluck('word')->toArray());

    $client = new Client([
        'verify' => false,
        'timeout' => 15
    ]);

    collect($notFounds)->each(function ($word) use ($client, $words) {
        $request = new \GuzzleHttp\Psr7\Request('GET', 'https://www.ldoceonline.com/dictionary/' . Str::slug($word, dictionary: [
                '\'' => '-'
            ]));

        $content = $client->easySend($request, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:126.0) Gecko/20100101 Firefox/126.0'
            ]
        ])->body;

        $re = '/<span.*data-src-mp3="(?<sound>.*?)".?.*>/m';

        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        $new = collect($matches)->take(2)->map(fn($item) => Str::before($item['sound'], '?'));

        if (!$new->filter(fn($item) => Str::contains($item, 'bre'))->first())
            return;

        $newWord = Word::create([
            Word::WORD => $word,
            Word::BRE => $new->filter(fn($item) => Str::contains($item, 'bre'))->first(),
            Word::AME => $new->filter(fn($item) => Str::contains($item, 'ame'))->first()
        ]);

        $words->add($newWord);
    });

    return view('welcome', ['vocabularies' => $words]);
});
