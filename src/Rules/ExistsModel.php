<?php

namespace Uasoft\Badaso\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsModel implements Rule
{
    /** @var string */
    protected $modelClassName;

    /** @var string */
    protected $modelAttribute;

    /** @var string */
    protected $attribute;

    /** @var string */
    protected $modelId;

    public function __construct(string $modelClassName, string $attribute = 'id')
    {
        $this->modelClassName = $modelClassName;

        $this->modelAttribute = $attribute;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        $this->modelId = $value;

        if (! is_array($value)) {
            $this->modelId = [$value];
        }

        $model = $this->modelClassName::whereIn($this->modelAttribute, $this->modelId)->pluck($attribute)->toArray();
        $modelCount = count($model);

        return array_count_values($model)[$value] === $modelCount;
    }

    public function message(): string
    {
        $modelId = implode(', ', $this->modelId);

        $classBasename = class_basename($this->modelClassName);

        return __('badaso::validation.rule.exists_model', [
            'attribute' => $this->attribute,
            'model' => $classBasename,
            'modelAttribute' => $this->modelAttribute,
            'modelId' => $modelId,
        ]);
    }
}
