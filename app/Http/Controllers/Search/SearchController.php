<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\Discussions\SearchDiscussionResource;
use App\Models\Discussion;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('query');
        
        $discussions = Discussion::search($query)->paginate();

        return SearchDiscussionResource::collection($discussions);
    }
}
