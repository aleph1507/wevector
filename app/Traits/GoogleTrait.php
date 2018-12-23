<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Storage;

trait GoogleTrait
{

    public function new_folder($driveService, $name, $folder = null)
    {
        $metaArray = ['name' => $name, 'mimeType' => 'application/vnd.google-apps.folder'];
//        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
//            'name' => $name,
//            'mimeType' => 'application/vnd.google-apps.folder'));

        if($folder != null)
            $metaArray['parents'] = $folder;

        $fileMetadata = new \Google_Service_Drive_DriveFile($metaArray);
        $file = $driveService->files->create($fileMetadata, array(
            'fields' => 'id'));

        return $file->id;
    }

    public function drive_image($name, $path, $service, $folder)
    {
//        dd($folder);
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => $name,
            'parents' => $folder
        ));
        $content = file_get_contents(public_path($path . $name));
        $file = $service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'image/*',
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        return $file;
    }

    public function get_drive_service()
    {
        $client = new \Google_Client();
        $client->setApplicationName('WeVector');
        if(!$cred_file = $this->getOAuthCredentialsFile())
            return 'No Credentials File.';
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$cred_file");
        $client->useApplicationDefaultCredentials();
        $value = "aleksandar@thinkerlab.io";
        $client->setSubject($value);
        $client->addScope("https://www.googleapis.com/auth/drive");
        return new \Google_Service_Drive($client);
    }

    function getOAuthCredentialsFile()
    {
        return Storage::exists('oauth-credentials.json') ?
            Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()
            . 'oauth-credentials.json' : false;
    }

}
