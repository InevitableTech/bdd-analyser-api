<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource extends JsonResource
{
    protected $expose = [];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = $this->expose($this->resource);

        return $array;
    }

    protected function expose(Model $data): array
    {
        $response = [];

        foreach ($this->expose as $property) {
            $response[$property] = $this->$property;
        }

        $response['uri'] = strtolower($this->getIdUrl($data->id));

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
