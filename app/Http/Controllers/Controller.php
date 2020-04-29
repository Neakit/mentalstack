<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * неймспейс компонентов для подготовки данных
     */
    const COMPONENTS_NAMESPACE = '\App\Components';

    /**
     * Show the application view.
     *
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($request)
    {
        $prefix = $request->route()->getPrefix();
        if($prefix == '/admin') {
            return view('admin');
        } else {
            return view('web');
        }
    }

    /**
     * Распределетель основных запросов данных. Предназначен для ajax запросов
     * Принимает запросы от роута /$model/$action/$additional
     * @param Request $request
     * @param string $model - lower kebab-case имя класса-компонента из Web\Components
     * @param string $action - lower kebab-case имя метода который должен вернуть данные
     * @param null $additional - параметры которые необходимо передать в метод, опционально
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function locator(Request $request, string $model, string $action, $additional = null)
    {
        /**
         * Если запрос не ajax, значит открытие страницы - возвращаем базовую вьюху
         * Vue router дальше отрисовывает
         */
        if(!$request->ajax()) {
            return $this->index($request);
        }

        /**
         * @var BaseComponent $component  - получаем компонент отвечающий за обработку данных для раздела. Request пробрасывается в его конструктор
         */

        if ($component = $this->getSectionComponentClass($model, $request)) {
            $method = Str::camel($action);

            /**
             * @var $result  - получаем данные от метода полученного из url.
             * Если метод не найден - отработает перехватчик и будет вызван метод default вызываеммого компонента или базового класса
             */
            $result = $component->$method($request, $additional);
            /** может вернуться объект response или данные. если  вернулся response возвращаем его.
             * предназначено для возвращения ответа с доп.данными из компонента (http code, cookies, etc..)
             */
            switch (true) {
                case $result instanceof JsonResponse:
                    return $result;
                case $result instanceof Response:
                    return $result;
                case \is_array($result) && array_key_exists('http_status', $result) && $result['http_status']:
                    return response($result, $result['http_status']);

                case \is_array($result) && array_key_exists('success', $result):
                    return response($result, $result['success'] ? 200 : 422);

                default:
                    return response($result, 200);
            }
        }

        /**
         * Если компонент для обработки запроса не найден - 404-я ошибка
         */
        return response()->json([
            'success' => false,
            'errors' => ['content' => 'Not Found'],
        ], 422);
    }

    /**
     * @param string $model
     * @param Request|null $request
     * @return mixed|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getSectionComponentClass(string $model, ?Request $request = null)
    {
        $model = Str::camel($model);
        $sModelName = static::COMPONENTS_NAMESPACE . "\\" . ucfirst($model);
        /**
         * Проверяем наличие класса и создаем его экземпляяр с пробросом текушущего Request
         */
        if (class_exists($sModelName)) {
            return app()->make($sModelName, [$request]);
        } else {
            return null;
        }
    }
}
