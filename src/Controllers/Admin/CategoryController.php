<?php
namespace App\Controllers\Admin;

use App\Models\Admin\CategoryModel;
use Exception;

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function getCategories() {
        try {
            return $this->categoryModel->getAllCategories();
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve categories: " . $e->getMessage());
        }
    }

    public function createCategory($title) {
        try {
            if (empty($title)) {
                throw new Exception("Category title is required");
            }
            return $this->categoryModel->createCategory($title);
        } catch (Exception $e) {
            throw new Exception("Failed to create category: " . $e->getMessage());
        }
    }

    public function updateCategory($id, $title) {
        try {
            if (empty($id) || empty($title)) {
                throw new Exception("Category ID and title are required");
            }
            return $this->categoryModel->updateCategory($id, $title);
        } catch (Exception $e) {
            throw new Exception("Failed to update category: " . $e->getMessage());
        }
    }

    public function getCategoryById($id) {
        try {
            if (empty($id)) {
                throw new Exception("Category ID is required");
            }
            return $this->categoryModel->getCategoryById($id);
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve category: " . $e->getMessage());
        }
    }
    public function deleteCategory($id) {
        try {
            if (empty($id)) {
                throw new Exception("Category ID is required");
            }
            
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                throw new Exception("Category not found");
            }
            
            return $this->categoryModel->deleteCategory($id);
        } catch (Exception $e) {
            throw new Exception("Failed to delete category: " . $e->getMessage());
        }
    }
}
