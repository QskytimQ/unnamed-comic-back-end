<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Storage;
use Validator;
use Illuminate\Http\Request;
use App\Repositories\ComicRepository;

class PublishController extends Controller
{
    public function __construct(ComicRepository $comicRepo)
    {
        $this->comicRepo = $comicRepo;
    }

    public function comic(Request $request)
    {
        $user = Auth::user();
        $data = $request->only('name', 'summary', 'author', 'types', 'cover');
        $validator = $this->comicValidator($data);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }

        $data['types'] = json_encode($data['types']);
        $data['published_by'] = $user->id;
        $comic = $this->comicRepo->create($data);

        if ($cover = $data['cover']) {
            $extension = explode('/', File::mimeType($cover))[1];
            $data['cover'] = time().'.'.$extension;
            $this->saveFile('comics/'.$comic->id.'/covers/'.$data['cover'].'.'.$extension, $cover);
            $this->comicRepo->updateCover($comic['id'], $data['cover']);
            $comic = $this->comicRepo->show($comic['id']);
        }

        $comic['types'] = $request->types;
        $comic['published_by'] = ['id' => $user->id, 'name' => $user->name];

        return response()->json(['status' => 'success', 'comic' => $comic]);
    }

    private function saveFile($path, $file)
    {
        return 
            Storage::put(
                $path,
                file_get_contents($file->getRealPath())
            );
    }

    private function comicValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'summary' => 'required|max:255',
            'author' => 'required|max:255',
            'types' => 'required|array',
            'types.*' => 'required|exists:types,name',
            'cover' => 'image|nullable',
        ]);
    }
}
