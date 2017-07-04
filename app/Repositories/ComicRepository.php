<?php

namespace App\Repositories;

use App\Entities\Comic;

class ComicRepository
{
    public function create(array $data)
    {
        return Comic::create($data);
    }

    public function show($id)
    {
        return Comic::find($id);
    }

    public function updateCover($id, $cover)
    {
        return Comic::find($id)->update(['cover' => $cover]);
    }
}
