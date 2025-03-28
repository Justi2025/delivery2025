<?php
 

namespace App\Servsi\Faili;

use App\Common\Perechislenia\FailoviTipPerechislenie;
use App\Models\File;
use App\Servsi\Authentication\ZaloginenniPolzovatel;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

class ServisZagruzkiFailov
{
    public function __construct(
        private readonly ImageManager $imageManager
    )
    {
    }

    public function uploadX(
        UploadedFile             $file, ZaloginenniPolzovatel $user,
        ?FailoviTipPerechislenie $fileable_type = null, ?string $fileable_id = null,
        string                   $destination = 'uploads',
    ): SozdanniFail
    {
        return $this->zagruzit($file, $user, $destination, $fileable_type?->value, $fileable_id);
    }

    /**
     * @param UploadedFile $file
     * @param ZaloginenniPolzovatel $user
     * @param string $destination uploads|avatars
     * @param string|null $owner_type
     * @param string|null $owner_id
     * @return SozdanniFail
     */
    public function zagruzit(
        UploadedFile $file, ZaloginenniPolzovatel $user,
        string       $destination = 'uploads',
        string       $owner_type = null, string|int $owner_id = null
    ): SozdanniFail
    {
        $clientOriginalName = $file->getClientOriginalName();

        $fileName = sprintf('%s.%s', md5($clientOriginalName . microtime(true)), $file->extension());

        $type = $file->getClientMimeType();
        $size = $file->getSize();

        $array = [
            'user_id' => $user->id,
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            'size' => $size,
            'fileable_type' => $owner_type,
            'fileable_id' => $owner_id
        ];


        if (in_array($file->extension(), ['jpg', 'jpeg', 'png'])) {
            $image = $this->imageManager->make($file)->orientate();

            if ('avatars' === $destination) {
                $image_path = public_path('images/avatars') . DIRECTORY_SEPARATOR . $fileName;
                $array['object_source'] = 'account.avatar';
                $saved_image = $image->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($image_path, 100);

            } else {
                $image_path = public_path('upload') . DIRECTORY_SEPARATOR . $fileName;
                $saved_image = $image->save($image_path, 20);
            }

            $array['size'] = $saved_image?->filesize() ?? 0;

        } else {
            $file->move(public_path('upload'), $fileName);
        }

        $createdFile = File::create($array);

        return SozdanniFail::from([
            'id' => $createdFile->id,
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            'size' => $array['size'],
            'created_at' => $createdFile->created_at,
            //'object_source' => $createdFile->object_source,
        ]);
    }
}