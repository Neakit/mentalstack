<?php

namespace App\Components;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Products extends BaseComponent
{
    public $modelName = 'Products';

    public $modelNameSpace = 'App\Models\\';

    public $dbModel = null;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Добавление продукта
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) {

        $files = $request->file('files');
        $data = $request->input();
        $images = [];

        if(!is_null($files)) {
            foreach ($files as $file) {
                $name = time()."_".$file->getClientOriginalName();
                $path = base_path().'/public/images/products';
                $file->move($path, $name);
                array_push($images, $name);
            }
        }

        $data['images'] = json_encode($images);

        $response = array_filter($data, function($key) {
            if (in_array($key, $this->aColumns)) {
                return $key;
            }
        }, ARRAY_FILTER_USE_KEY);

        $data = $this->dbModel->create($response);
        $data['images'] = $images;

        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    private function deleteFile(String $name)
    {
        $file_path = base_path().'/public/images/products/'.$name;

        if(File::exists($file_path)) {
            File::delete($file_path);
        }
    }

    private function saveFileImages($files, $nId)
    {
        if(!is_null($files)) {
            $model = $this->dbModel->where('id', $nId)->first();

            $dbImages = json_decode($model['images']);
            foreach ($files as $file) {
                $name = time()."_".$file->getClientOriginalName();
                $path = base_path().'/public/images/products';

                $file->move($path, $name);
                array_push($dbImages, $name);
            }

            $dbImages = json_encode($dbImages);
            $model->fill(['images' => $dbImages]);
            $model->save();
        }
    }

    private function mergeImages(String $images, $nId) {
        $model = $this->dbModel->where('id', $nId)->first();
        // images from database
        $dbImages = json_decode($model['images']);
        // post images
        $postImages = json_decode($images);
        $deletedImages = array_diff($dbImages, $postImages);

        foreach ($deletedImages as $name) {
            $this->deleteFile($name);
        }

        $postImages = json_encode($postImages);

        $model->fill(['images' => $postImages]);
        $model->save();
    }

    /**
     * Сохранение отредактированных полей
     * @param Request $request
     * @param string $nId
     * @return JsonResponse
     */
    public function edit(Request $request,  $nId) {
        $data = $request->all();
        $this->mergeImages($data['images'], $nId);
        $this->saveFileImages($request->file('files'), $nId);
        $model = $this->dbModel->where('id', $nId)->first();

        $data['images'] = $model->images;
        $response = array_filter($data, function($key) {
            if (in_array($key, $this->aColumns)) {
                return $key;
            }
        },ARRAY_FILTER_USE_KEY );

        $model->fill($response);
        $model->save();

        $data['images'] = json_decode($data['images']);

        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    /**
     * Получить данные с фильтром
     * @param Request $request
     * @param null $additional
     * @return JsonResponse
     */
    public function getRecordsWithParams(Request $request, $additional = null) {
        $requestParams = $request->all();

        $skip    = Arr::get($requestParams, 'start', 1) - 1;
        $limit   = Arr::get($requestParams, 'length', null);
        $filter  = Arr::get($requestParams, 'filter', null);
        $sortRow = Arr::get($requestParams, 'sortRow', null);
        $sort    = Arr::get($requestParams, 'sort', null);

        $query = $this->dbModel::with(['category', 'productModel']);
        if ($filter != null) {
            $i = 0;
            foreach ($this->aFindFields as $field) {
                $query = ($i == 0) ? $query->where($field, 'like', '%' . $filter . '%') : $query = $query->Orwhere($field, 'like', '%' . $filter . '%');
                $i++;
            }
        }

        $query = ($sortRow != null) ? $query->orderBy($sortRow, $sort) : $query->orderBy('id');
        $count = $query->count();
        $data  = ($limit != null ) ? $query->take($limit)->skip($skip)->get() : $query->get();

        foreach ($data as $product) {
            $product['images'] = json_decode($product['images']);
        }

        return response()->json([
            'count' => $count,
            'data' => $data,
        ]);
    }
}
