<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

trait FileUploadTrait
{
    public function saveFiles(Request $request)
    {
        if (! file_exists(public_path('uploads'))) {
            mkdir(public_path('uploads'), 0777);
            mkdir(public_path('uploads/thumb'), 0777);
        }

        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                    $filename  = time() . '-' . $request->file($key)->getClientOriginalName();
                    $file      = $request->file($key);
                    $image     = Image::read($file);
                    $maxWidth  = $request->{$key . '_max_width'};
                    $maxHeight = $request->{$key . '_max_height'};

                    if (! file_exists(public_path('uploads/thumb'))) {
                        mkdir(public_path('uploads/thumb'), 0777, true);
                    }

                    Image::read($file)->scaleDown(50, 50)->save(public_path('uploads/thumb') . '/' . $filename);

                    if ($image->width() > $maxWidth && $image->height() > $maxHeight) {
                        $image->scaleDown($maxWidth, $maxHeight);
                    } elseif ($image->width() > $maxWidth) {
                        $image->scaleDown(width: $maxWidth);
                    } elseif ($image->height() > $maxHeight) {
                        $image->scaleDown(height: $maxHeight);
                    }

                    $image->save(public_path('uploads') . '/' . $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {
                    $filename = time() . '-' . $request->file($key)->getClientOriginalName();
                    $request->file($key)->move(public_path('uploads'), $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }

        return $finalRequest;
    }
}
