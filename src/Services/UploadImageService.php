<?php

namespace App\Services;

use Symfony\Component\Filesystem\Filesystem;

class UploadImageService {

    public function __construct(
        private Filesystem $fs,
        private string $profileFolder,
        private string $profileFolderPublic


        )
    {

    }
    public function uploadProfileImage($picture, $oldPicture = null)
    {
        $extension = $picture->guessExtension() ?? 'bin';
        $filename = bin2hex(random_bytes(10)) . "." . $extension; // avatar.png => audyqiwdbhdsa.png
        $picture->move($this->profileFolder, $filename);

        if($oldPicture) {
            $this->fs->remove($this->profileFolder . "/" . pathinfo($oldPicture, PATHINFO_BASENAME));
        }

        return $this->profileFolderPublic . '/' . $filename;
    }
}

?>