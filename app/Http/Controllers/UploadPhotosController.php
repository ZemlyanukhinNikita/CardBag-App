<?php namespace App\Http\Controllers;

use app\Repositories\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadPhotosController extends Controller
{
    /**
     * @param Request $request
     * @param UserInterface $userRepository
     * @return \Illuminate\Http\JsonResponse
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function uploadPhoto(Request $request, UserInterface $userRepository)
    {
        $this->validate($request, [
            'file' => 'required|image',
        ]);

        if (!$user = $userRepository->findOneBy('uuid', $request->header('uuid'))) {
            abort(401, 'Unauthorized');
        }

        if ($request->file('file')->isValid()) {
            $file = $request->file('file')->move('storage/');
            return response()->json(['url' => Storage::url($file)]);
        }
        abort(400, 'File upload failed');
    }
}
