<?php
namespace App\Service\impl;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService {
  public function __construct(
    private readonly string $uploadDir,private SluggerInterface $slugger
  ) {

  }
    public function uploadFile($file): string {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
        try {
        $file->move($this->uploadDir, $newFilename);
        } catch (FileException $e) {
        throw new \Exception('Failed to upload file: '.$e->getMessage());
        }
    
        return $newFilename;
    }
}