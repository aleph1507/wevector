<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Storage;

trait GoogleTrait
{

    public function add_domain_permissions($driveService, $fileId) {
        $domain_permission = new \Google_Service_Drive_Permission([
            'type' => 'domain',
            'role' => 'writer',
            'domain' => 'thinkerlab.io'
        ]);

        try {
            $driveService->permissions->create($fileId, $domain_permission);
        } catch (Exception $e) {
            throw new HttpServerErrorException();
        }

        return $domain_permission;
    }

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

    public function folder_exist($driveService, $name)
    {
        $pageToken = null;
        $response = null;
        $file_id = false;
        do {
            $response = $driveService->files->listFiles(array(
                'q' => "mimeType='application/vnd.google-apps.folder' and trashed=false",
                'spaces' => 'drive',
                'pageToken' => $pageToken,
                'fields' => 'nextPageToken, files(id, name)',
            ));
            foreach ($response->files as $file) {
                if($file->name == $name) {
                    $file_id = $file->id;
                }
            }

            $pageToken = $response->pageToken;
        } while ($pageToken != null);

        return $file_id ? $file_id : $this->new_folder($driveService, $name);
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
        $value = "info@thinkerlab.io";
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
