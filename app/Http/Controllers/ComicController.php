<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Storage;
use Response;

class ComicController extends Controller
{
    private $comicRepo;
    private $chapterRepo;

    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
    }

    public function show($id)
    {
        $comic = $this->comicRepo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'msg' => 'Comic not found.']);

        $chapters = $this->chapterRepo->showAll($id);
        return response()->json(['status' => 'success', 'comic' => $comic, 'chapters' => $chapters]);
    }

    public function showCover($id)
    {
        $comic = $this->repo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'msg' => 'Comic not found.']);
        $cover_path = Storage::files('comics/'.$comic->id);
        return Response::download(storage_path().'/app/'.$cover_path[0]);
    }
}
