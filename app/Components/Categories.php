<?php

namespace App\Components;

/**
 * Класс для работы с моделью Categories
 */
class Categories extends BaseComponent {

    public $modelName = 'Categories';

    public $modelNameSpace = 'App\Models\\';

    public $aFields = [
        'id' => 'id',
        'title' => 'Название категории'
    ];

    public $dbModel = null;

    public function __construct() {
        parent::__construct();
    }
}
