<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';

class gdrive{

  function __construct() {
    $credentialFile = FCPATH . 'gdrive/credentials.json';
    $tokenFile = FCPATH . 'gdrive/token.json';
    $client = new Google_Client();
    $client->setAuthConfig($credentialFile);
    $client->setScopes("https://www.googleapis.com/auth/drive");
    $accessToken = json_decode(file_get_contents($tokenFile), true);
    $client->setAccessToken($accessToken);

    // init API Service
    $this->service = new Google_Service_Drive($client);
  }

  function unggah($berkas){
    // unggah berkas
    $folderId = '1CIpOXWo0-pq6iInqr0ibA2ZlgVaZreUZ';
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $berkas,
        'parents' => array($folderId)
    ));

    $content = file_get_contents(FCPATH . 'public/' . $berkas);
    $file = $this->service->files->create($fileMetadata, array(
        'data' => $content,
        'mimeType' => 'application/pdf',
        'uploadType' => 'media'
      )
    );

    return $file->id;
  }

  function unggah_share ($berkas) {
    // sharing berkas
    $fileId = $this->unggah($berkas);
    $userPermission = new Google_Service_Drive_Permission(array(
      'type' => 'anyone',
      'role' => 'reader'
    ));

    $request = $this->service->permissions->create(
      $fileId, 
      $userPermission, 
      array('fields' => 'id')
    );

    // kembalikan url yang sudah siap
    // return "https://drive.google.com/file/d/". $fileId ."/view?usp=sharing";
    return $fileId;
  }

  function hapus($file_id) {
    $this->service->files->delete($file_id);
  }
}