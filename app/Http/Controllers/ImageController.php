<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Google\Client;
use Google\Service\Drive;
// use Google_Client;
// use Google_Service_Drive;
// use Google_Service_Drive_DriveFile;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:16776',
        ]);
        
        // Get the uploaded file
        $file = $request->file('image');
        
        // Upload the file to Google Drive
        $googleDriveFileId = $this->uploadToGoogleDrive($file);

        if ( !$googleDriveFileId ) {
            return response()->json([
                'message' => 'Image upload failed.'
            ], 500);
        }
        
        // Save information to the database
        $image = new Image();
        $image->file_name = $file->getClientOriginalName();
        $image->file_path = $googleDriveFileId;
        $image->person = 'tester';
        $image->save();
        
        // Return a response
        return response()->json([
            'message' => 'Image uploaded successfully.',
            'data' => $image
        ]);
    }
    
    private function uploadToGoogleDrive($file)
    {
        // Set up your Google Drive API credentials
        $serviceAccountKeyFile = storage_path('app/private/bk-playground-389222-0e3c61e7ad8a.json');
        
        try {
            $googleClient = new Client();
            $googleClient->setAuthConfig($serviceAccountKeyFile);
            $googleClient->addScope(Drive::DRIVE);
            $googleClient->addScope(Drive::DRIVE_METADATA);
            
            $driveService = new Drive($googleClient);
            
            // Set up the folder ID in Google Drive where you want to upload the files
            $folderId = env('GOOGLE_DRIVE_FOLDER_ID');
            
            // Upload the file to Google Drive
            $fileMetadata = new Drive\DriveFile([
                'name' => $file->getClientOriginalName(),
                // 'name' => 'test.jpg',
                'parents' => [$folderId],
            ]);
            
            $content = file_get_contents($file->getRealPath());
            
            $uploadedFile = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id, parents',
                'supportsAllDrives' => true,
            ]);
        } catch(Exception $e) {
            dd($e);
            return null;
        }

        return $uploadedFile->id;
    }
}
