<?php

namespace App\Components;

class ProductModels extends BaseComponent
{
    public $modelName = 'ProductModels';

    public $modelNameSpace = 'App\Models\\';

    /**
     * @var array - fillable поля в таблице
     */
    public $aFields = [
        'id' => 'id',
        'title' => 'Название категории'
    ];

    public $dbModel = null;

    public function __construct() {
        parent::__construct();
    }
}
