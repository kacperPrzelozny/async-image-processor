<?php

namespace App\Http\Api;

use App\Enums\ImageAction;
use App\Enums\ImageStatus;
use App\Http\Requests\AddImageRequest;
use App\Jobs\AddWatermarkJob;
use App\Jobs\ChangeDimensionsJob;
use App\Jobs\ConvertToWebpJob;
use App\Models\Image;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function index()
    {

    }

    public function store(AddImageRequest $request): JsonResponse
    {
        $file = $request->file('image');
        $path = $file->store('images', 'public');
        if (!$path) {
            return $this->responseError(
                "There was an error while saving the file in storage.",
                code: 500
            );
        }

        $image = new Image();
        $image->fill([
            'original_name' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'status' => ImageStatus::PENDING,
        ]);

        try {
            $image->saveOrFail();
        } catch (\Throwable $e) {
            return $this->responseError(
                "There was an error while saving the file in database.",
                [
                    "image" => $e->getMessage(),
                ],
                500
            );
        }

        try {
            $action = ImageAction::from($request->get('action'));

            match ($action) {
                ImageAction::DIMENSIONS => ChangeDimensionsJob::dispatch(
                    $image,
                    (int) $request->get('width'),
                    (int) $request->get('height'),
                ),
                ImageAction::WEBP => ConvertToWebpJob::dispatch($image),
                ImageAction::WATERMARK => AddWatermarkJob::dispatch(
                    $image,
                    $request->get('watermark'),
                ),
            };
        } catch (\Throwable $e) {
            return $this->responseError(
                "There was an error while dispatching job.",
                [
                    "dispatch" => $e->getMessage(),
                ],
                500
            );
        }

        return $this->responseSuccess($image->toArray());
    }
}
