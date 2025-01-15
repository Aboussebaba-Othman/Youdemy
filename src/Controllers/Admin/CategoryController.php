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

   
}