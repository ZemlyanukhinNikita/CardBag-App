<?php namespace App\Http\Controllers;

use app\Repositories\PhotoInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadPhotosController extends Controller
{
    /**
     * @param Request $request
     * @param PhotoInterface $photoRepository
     * @return \Illuminate\Http\JsonResponse
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function uploadPhoto(Request $request, PhotoInterface $photoRepository)
    {
        $this->validate($request, [
            'file' => 'required|image|max:20000',
        ]);

        if ($request->file('file')->isValid()) {
            $filename = md5(time() . $request->file('file')) . '.' . $request->file('file')->guessExtension();
            $file = $request->file('file')->move('storage/', $filename);

            $photoRepository->create(['user_id' => $request->user()->id, 'filename' => $filename]);

            return response()->json(['url' => Storage::url($file)]);
        }
        abort(400, 'File upload failed');
    }
}
