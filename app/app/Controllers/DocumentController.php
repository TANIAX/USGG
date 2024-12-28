<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\FileRepository;
use App\Repositories\BaseRepository;
use App\Entities\File;
use App\Helpers\FileHelper;

class DocumentController extends BaseController
{

    private FileRepository $fileRepository;
    
    public function __construct()
    {
        $this->fileRepository = new FileRepository();
    }


    public function index()
    {
        $files = $this->fileRepository->getAll(FileRepository::FILE_TYPE_GUIDE, BaseRepository::RESULT_AS_CUSTOM, File::class) ?? [];
        return view('pages/admin/document/index', [
            'files' => json_encode($files),
            'file_types' => json_encode([...FileRepository::FILE_TYPES, 'TOUS'])
        ]);
    }

    /**
     * Deletes provided document.
     *
     * @param mixed $ids The ID(s) of the document(s) to be deleted separated by commas.
     */
    public function delete($ids)
    {
        $ids = explode(',', $ids);

        //Gets each document by its ID before deleting it because we need to know the path to the file to delete it from the system and then proceed to delete it from the database.
        foreach ($ids as $id) {
            $file = $this->fileRepository->getById($id, BaseRepository::RESULT_AS_CUSTOM, File::class);
            if ($file === null) {
                continue;
            }

            $path = ROOTPATH . 'public'. DIRECTORY_SEPARATOR . FileHelper::DOCUMENTS_DIRECTORY . $file->path . DIRECTORY_SEPARATOR . $file->name;

            //Depending on the OS, the path may need to be modified
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $path = str_replace('/', '\\', $path);
            }

            if (file_exists($path)) {
                $deleted = unlink($path);
            }

            $this->fileRepository->delete($id, true);
        }

        return redirect()->to(base_url('/admin/document'));
    }

    public function create()
    {
        return view('pages/admin/document/create',
            ['file_types' => json_encode(FileRepository::FILE_TYPES)]);
    }

    public function upload()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'file_type' => $this->request->getPost('file_type'),
            'file' => $this->request->getFile('file'),
        ];

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'file_type' => 'required|in_list[' . implode(',', FileRepository::FILE_TYPES) . ']',
            'file' => [
                'uploaded[file]',
                'max_size[file,10240]',
                'mime_in[file,application/pdf,text/plain, application/vnd.openxmlformats-officedocument.wordprocessingml.document]',
                'ext_in[file,doc,docx,txt,pdf]',
            ],
        ];

        
        if (!$this->validateData($data, $rules)) {
            $errors = $this->validator->getErrors();
            $this->session->setFlashdata('errors', $errors);
            return redirect()->to(base_url('/admin/document/create'));
        }

        //Set the path where the file will be stored
        $path = ROOTPATH . 'public'. DIRECTORY_SEPARATOR . FileHelper::DOCUMENTS_DIRECTORY . strtolower($data['file_type']) . DIRECTORY_SEPARATOR;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
            $path = str_replace('/', '\\', $path);

        //Set the file name
        $fullPath = $path . $data['name'] . '.' . $data['file']->getExtension();

        //Check if the directory exists, if not, create it
        if (!file_exists($path))
            mkdir($path, 0777, true);

        if(file_exists($fullPath)) {
            $data['name'] = $data['name'] . '_' . time();
            $fullPath = $path . $data['name'] . '.' . $data['file']->getExtension();
        }

        //Move the file to the folder
        if($data['file']->move($path, $data['name'] . '.' . $data['file']->getExtension())) {
            $this->fileRepository->insert([
                'name' => $data['name'] . '.' . $data['file']->getExtension(),
                'path' => strtolower($data['file_type']),
                'file_type' => $data['file_type'],
            ]);
        }else{
            die('Error while uploading the file');
        }

        return redirect()->to(base_url('/admin/document'));
    }
}
