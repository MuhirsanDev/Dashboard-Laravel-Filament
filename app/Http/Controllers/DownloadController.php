<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadController extends Controller
{
    public function download($post)
    {
        $data = Media::select('id', 'file_name')->where('model_id', $post)->first();

        $content = Storage::disk('public')->path($data->id . '/' . $data->file_name);

        return response()->download($content);
    }
}
