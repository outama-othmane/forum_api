<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Resources\Channels\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
    	$channels = Channel::latest()
    	->get();
    	// ->paginate(
    	// 	min($request->get('perPage', 10), 10)
    	// ); 
    	return ChannelResource::collection($channels);
    }
}
