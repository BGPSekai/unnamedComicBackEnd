<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\TagRepository;
use Auth;

class TagController extends Controller
{
    public function __construct(TagRepository $repo)
    {
        $this->repo = $repo;
        $this->middleware('jwt.auth', ['except' => ['show']]);
    }

    public function store($name, $comic_id)
    {
    	$data = ['comic_id' => $comic_id, 'tag' => $name, 'tag_by' => Auth::user()->id];

    	$tag = $this->repo->store($data);

        if (!$tag)
            return response()->json(['status' => 'error', 'message' => 'Tag Exist']);

    	return response()->json(['status' => 'success', 'tag' => $tag]);
    }

    public function destroy($name, $comic_id)
    {
        try {
            $this->repo->destroy($name, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Tag Not Found'], 404);
        }

    	return response()->json(['status' => 'success']);
    }

    public function show($name, $page)
    {
        $comics = $this->repo->find($name, $page);
        return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function count($name)
    {
        return response()->json(['status' => 'success', 'pages' => $this->repo->count($name)]);
    }
}