<?php

namespace Uasoft\Badaso\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueModel implements Rule
{
    /** @var string */
    protected $modelClassName;

    /** @var string */
    protected $modelAttribute;

    /** @var string */
    protected $attribute;

    /** @var string */
    protected $modelId;

    /** @var string */
    protected $except;

    public function __construct(string $modelClassName, string $attribute = 'id', string $except = 'NULL')
    {
        $this->modelClassName = $modelClassName;

        $this->modelAttribute = $attribute;

        $this->except = $except;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        $this->modelId = $value;

        if (!is_array($value)) {
            $this->modelId = [$value];
        }

        $modelCount = $this->modelClassName::whereIn($this->modelAttribute, $this->modelId);

        if (!is_null($this->except)) {
            $modelCount->where('id', '!=', $this->except);
        }

        $modelCount = $modelCount->count();

        return count($this->modelId) !== $modelCount;
    }

    public function message(): string
    {
        $modelId = implode(', ', $this->modelId);

        $classBasename = class_basename($this->modelClassName);

        return __('badaso::validation.rule.unique_model', [
            'attribute' => $this->attribute,
            'model' => $classBasename,
            'modelAttribute' => $this->modelAttribute,
            'modelId' => $modelId,
        ]);
    }
}
