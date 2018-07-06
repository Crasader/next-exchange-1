<?php

if (! function_exists('user_from_slug')) {
    /**
     * Fetches user by slug
     *
     * @param $slug
     * @return mixed
     */
    function user_from_slug($slug)
    {
        return \App\Models\User::whereHas('profile', function ($has) use ($slug) {
            $has->where('slug', $slug);
        })->firstOrFail();
    }
}

if (! function_exists('transformModel')) {
    /**
     * Transform model
     *
     * @param $data
     * @param $transformer
     * @param array $meta
     * @param array $includes
     * @return \Spatie\Fractal\Fractal
     */
    function transformModel($data, $transformer, $meta = [], $includes = [])
    {
        return fractal($data, $transformer)
            ->withResourceName('data')
            ->addMeta($meta)
            ->parseIncludes($includes);
    }
}
