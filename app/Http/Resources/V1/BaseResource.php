<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource extends JsonResource
{
    protected $expose = [];

    /**
     * 'relation' => ['value' => 'column'], usually used like this: 'analysis' => ['id' => 'name']
     */
    protected $relations = [];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = $this->expose($request, $this->resource);

        return $array;
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'success' => true,
        ];
    }

    protected function expose(Request $request, Model $data = null): array
    {
        $response = [];

        if ($data === null) {
            return $response;
        }

        foreach ($this->expose as $property) {
            $response[$property] = $this->$property;
        }

        if ($data->id) {
            $response['uri'] = strtolower($this->getIdUrl($data->id));
        }

        if ($request->query('relations', false)) {
            foreach ($this->relations as $relation => $pluck) {
                $response['relations'][$relation] = $this->resource->$relation()->pluck($pluck[0], $pluck[1]);
            }
        }

        return $response;
    }

    private function getIdUrl(int $id): string
    {
        $route = $this->getShortModelName();

        return "/$route/$id";
    }

    protected function getShortModelName(): string
    {
        return str_replace('Resource', '', substr(strrchr(get_called_class(), '\\'), 1));
    }
}
