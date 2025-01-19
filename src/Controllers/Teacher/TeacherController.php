<?php
namespace App\Controllers\Teacher;
use App\Models\Teacher\CategoryModel;
use App\Models\Teacher\TagModel;

class TeacherController
{
    private CategoryModel $categoryModel;
    private TagModel $tagModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }

    public function getHomeData(): array
    {
        try {
            return [
                'categories' => $this->categoryModel->getCategories(),
                'tags' => $this->tagModel->getTags(),
                'stats' => $this->getTeacherStats()
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'categories' => [],
                'tags' => [],
                'stats' => [],
                'error' => 'Failed to fetch home data'
            ];
        }
    }

    private function getTeacherStats(): array 
    {
        return [
            'total_courses' => 0,
            'total_students' => 0,
            'total_revenue' => 0
        ];
    }
}