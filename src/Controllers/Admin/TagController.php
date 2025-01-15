<?php
namespace App\Controllers\Admin;

use App\Models\Admin\TagModel;
use Exception;

class TagController
{
    private $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function index() 
    {
        $tags = $this->tagModel->getAllTags();
        include __DIR__ . '/../../Views/admin/tag/home.php';
    }

    public function createMultipleTags()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tags'])) {
            $tags = trim($_POST['tags']);
            if (!empty($tags)) {
                try {
                    $this->tagModel->addTags($tags);
                    header('Location: ../../Views/admin/tag/home.php');
                    exit;
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    }

    public function deleteTag($id)
    {
        if (isset($id)) {
            try {
                $this->tagModel->deleteTag($id);
                header('Location: ../../Views/admin/tag/home.php');
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
?>
