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

abstract class Controller extends BaseController
{
    protected $limit = 100;

    protected $createInputs = [];

    protected $updateInputs = [];

    public function create(Request $request): JsonResource
    {
        try {
            $request->validate($this->createInputs);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage() . implode(': ', \Illuminate\Support\Arr::flatten($e->errors()))
            );
        }

        $model = $this->getModel();
        $data = $model::create($this->mapInputToModel($request));
        $this->afterCreate($request, $data);

        return $this->getResource($data);
    }

    public function find(Request $request, int $id): JsonResource
    {
        $modelName = $this->getModel();
        $model = $this->findByCriteria($request, $modelName)?->find($id);

        if ($model === null) {
            return $this->getResource();
        }

        return $this->getResource($model);
    }

    public function findAll(Request $request): ResourceCollection
    {
        $model = $this->getModel();
        $data = $this->findByCriteria($request, $model)?->get();

        if (! $data) {
            return $this->createResponse([]);
        }

        return $this->getResourceCollection($data);
    }

    public function update(Request $request, int $id): JsonResource
    {
        if (! $this->updateInputs) {
            throw new Exception('Update operation not allowed.');
        }

        $request->validate($this->updateInputs);

        $model = $this->getModel();
        $data = $model::update(['id' => $id], $this->mapInputToModel($request));

        return $this->getResource($data);
    }

    public function delete(Request $request, int $id): array
    {
        $model = $this->getModel();
        $model::delete(['id' => $id]);

        return $this->createResponse(true);
    }

    private function getResource(Model $data = null, $namespace = '\\App\Http\\Resources\\V1\\'): JsonResource
    {
        $resourceClass = $namespace . $this->getShortModelName() . 'Resource';

        return new $resourceClass($data);
    }

    private function getResourceCollection(Collection $data, $namespace = '\\App\Http\\Resources\\V1\\'): AnonymousResourceCollection
    {
        $resourceClass = $namespace . $this->getShortModelName() . 'Resource';

        return $resourceClass::collection($data)->additional(['success' => true]);
    }

    protected function getModel($namespace = '\\App\\Models\\'): string
    {
        return $namespace . $this->getShortModelName();
    }

    protected function mapInputToModel(Request $request): array
    {
        return [];
    }

    protected function getShortModelName(): string
    {
        return str_replace('Controller', '', substr(strrchr(get_called_class(), '\\'), 1));
    }

    protected function getUserForToken(string $token): User
    {
        $token = Token::find(['token' => $token]);

        return User::findId($token->user_project()->user_id);
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
