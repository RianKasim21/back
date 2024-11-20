<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ContentModel;
use CodeIgniter\API\ResponseTrait;

class ContentController extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $search = $this->request->getGet('search');
        $model = new ContentModel();

        if ($search) {
            $contents = $model->like('title', $search)->paginate(10);
        } else {
            $contents = $model->paginate(10);
        }

        return $this->respond([
            'status' => 'success',
            'data' => $contents,
            'pager' => $this->pager
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $model = new ContentModel();

        $contentData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'user_id' => $data['user_id']
        ];

        if ($model->insert($contentData)) {
            return $this->respondCreated(['message' => 'Content created successfully']);
        }

        return $this->fail('Content creation failed');
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $model = new ContentModel();

        $contentData = [
            'title' => $data['title'],
            'description' => $data['description']
        ];

        if ($model->update($id, $contentData)) {
            return $this->respond(['message' => 'Content updated successfully']);
        }

        return $this->fail('Content update failed');
    }

    public function delete($id = null)
    {
        $model = new ContentModel();
        
        if ($model->delete($id)) {
            return $this->respond(['message' => 'Content deleted successfully']);
        }

        return $this->fail('Content deletion failed');
    }
}
