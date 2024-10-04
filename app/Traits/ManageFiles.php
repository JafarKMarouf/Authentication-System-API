<?php

namespace App\Traits;

use Illuminate\Support\Facades\URL;

trait ManageFiles
{
    public function uploadFile($file, $directory): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalName . '_' . time() . '.' . $file->extension();
        $filePath = $file->storeAs($directory, $fileName);
        return URL::to('/') . '/storage/' . $filePath;
    }
}
