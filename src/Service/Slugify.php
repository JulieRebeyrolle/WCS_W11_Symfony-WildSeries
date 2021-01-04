<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input): string
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $input);
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[\p{P}]+/', '', $slug);
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = preg_replace('/\-{2,}/', '-', $slug);
        return $slug;
    }
}