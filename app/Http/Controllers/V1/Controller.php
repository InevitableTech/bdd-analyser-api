<?php

namespace App\Http\Controllers\V1;

use Exception;
use Illuminate\Routing\Controller as BaseController;
use App\Model\User;
use App\Events\ExampleEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\V1;

abstract class Controller extends BaseController
{
    protected $limit = 100;

    protected $transform = [];

    public static $createInputs = [];

    public static $updateInputs = [];

    public function create(Request $request): JsonResource
    {
        try {
            $inputs = $request->validate(static::$createInputs);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new Exception(
                $e->getMessage() . implode(': ', \Illuminate\Support\Arr::flatten($e->errors()))
            );
        }

        $model = $this->getModel();
        $inputs = $this->beforeCreate($request, $inputs);
        $data = $model::create($inputs);
        $this->afterCreate($request, $data);

        return $this->getResource($data);
    }

    protected function afterFind(Request $request, Model $model): void
    {
    }

    protected function afterFindAll(Request $request, $collection): void
    {
        foreach ($collection as $model) {
            $this->afterFind($request, $model);
        }
    }

    public function find(Request $request, int $id): JsonResource
    {
        $modelName = $this->getModel();
        $model = $this->findByCriteria($request, $modelName)?->find($id);
        $this->afterFind($request, $model);

        if ($model === null) {
            return $this->getResource();
        }

        return $this->getResource($model);
    }

    public function findAll(Request $request): ResourceCollection
    {
        $model = $this->getModel();
        $data = $this->findByCriteria($request, $model)?->take(100)->get();
        $this->afterFindAll($request, $data);

        if (! $data) {
            return $this->createResponse([]);
        }

        return $this->getResourceCollection($data);
    }

    public function update(Request $request, int $id): JsonResource
    {
        if (! static::$updateInputs) {
            throw new Exception('Update operation not allowed.');
        }

        $inputs = $request->validate(static::$updateInputs);

        $model = $this->getModel();
        $data = $model::update(['id' => $id], $inputs);

        return $this->getResource($data);
    }

    public function delete(Request $request, int $id): array
    {
        $model = $this->getModel();
        $model::delete(['id' => $id]);

        return $this->createResponse(true);
    }

    /**
     * This method facilitates the doc generation. Intention is to override in case you want to declare additional
     * rules not defined in the inputs array.
     */
    public static function createInputRules(): array
    {
        return static::$createInputs;
    }

    /**
     * This method facilitates the doc generation. Intention is to override in case you want to declare additional
     * rules not defined in the inputs array.
     */
    public static function updateInputRules(): array
    {
        return static::$updateInputs;
    }

    protected function getResource(Model $data = null, $namespace = '\\App\Http\\Resources\\V1\\'): JsonResource
    {
        $resourceClass = $namespace . $this->getShortModelName() . 'Resource';

        return new $resourceClass($data);
    }

    protected function getResourceCollection(Collection $data, $namespace = '\\App\Http\\Resources\\V1\\'): AnonymousResourceCollection
    {
        $resourceClass = $namespace . $this->getShortModelName() . 'Resource';

        return $resourceClass::collection($data)->additional(['success' => true]);
    }

    protected function getModel($namespace = '\\App\\Models\\'): string
    {
        return $namespace . $this->getShortModelName();
    }

    protected function getShortModelName(): string
    {
        return str_replace('Controller', '', substr(strrchr(get_called_class(), '\\'), 1));
    }

    protected function beforeCreate(Request $request, array $inputs): array
    {
        return $inputs;
    }

    protected function afterCreate(Request $request, Model $model): void
    {
    }

    protected function findByCriteria(Request $request, string $model): ?Builder
    {
        return $model::whereRelation('users', 'user_id', $request->user()->id);
    }

    protected function createResponse($data = [], bool $status = true, string $message = ''): array
    {
        $response = [
            'success' => $status,
            'data' => $data
        ];

        if ($message) {
            $response['message'] = $message;
            unset($response['data']);
        }

        return $response;
    }
}
