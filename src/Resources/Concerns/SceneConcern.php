<?php

namespace CrCms\Foundation\Resources\Concerns;

trait SceneConcern
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $scenes = [];

    /**
     * @var string
     */
    protected $scene;

    /**
     * @param string $scene
     *
     * @return SceneConcern
     */
    public function scene(string $scene): self
    {
        $this->scene = $scene;

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return SceneConcern
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param string $scene
     *
     * @return array
     */
    public function sceneFields(string $scene): array
    {
        return $this->scenes[$scene] ?? [];
    }
}