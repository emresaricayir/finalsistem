<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key',
        'name',
        'subject',
        'html_content',
        'text_content',
        'description',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get template by key
     */
    public static function getByKey($key)
    {
        return static::where('key', $key)->where('is_active', true)->first();
    }

    /**
     * Get all active templates
     */
    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Render template with variables using Blade compiler
     */
    public function render($variables = [])
    {
        try {
            // Use Blade compiler to render template with full Blade syntax support
            $html = Blade::render($this->html_content, $variables);
            $subject = Blade::render($this->subject, $variables);

            return [
                'subject' => $subject,
                'html_content' => $html,
                'text_content' => $this->text_content ? Blade::render($this->text_content, $variables) : null,
            ];
        } catch (\Exception $e) {
            \Log::error("Template rendering failed", [
                'template_key' => $this->key ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Replace object variables in template
     */
    private function replaceObjectVariables($content, $objectKey, $object)
    {
        try {
            // Replace simple object properties
            $objectString = $this->convertToString($object);
            $content = str_replace('{{ $' . $objectKey . ' }}', $objectString, $content);
            $content = str_replace('{{$' . $objectKey . '}}', $objectString, $content);

            // Replace object property access (e.g., $member->name)
            if (is_object($object)) {
                // Handle stdClass objects
                if ($object instanceof \stdClass) {
                    foreach ($object as $propertyName => $propertyValue) {
                        try {
                            $propertyString = $this->convertToString($propertyValue);
                            $content = str_replace('{{ $' . $objectKey . '->' . $propertyName . ' }}', $propertyString, $content);
                            $content = str_replace('{{$' . $objectKey . '->' . $propertyName . '}}', $propertyString, $content);
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                } else {
                    // For Eloquent models, get attributes directly
                    if (method_exists($object, 'getAttributes')) {
                        try {
                            foreach ($object->getAttributes() as $attributeName => $attributeValue) {
                                try {
                                    $attributeString = $this->convertToString($attributeValue);
                                    $content = str_replace('{{ $' . $objectKey . '->' . $attributeName . ' }}', $attributeString, $content);
                                    $content = str_replace('{{$' . $objectKey . '->' . $attributeName . '}}', $attributeString, $content);
                                } catch (\Exception $e) {
                                    continue;
                                }
                            }
                        } catch (\Exception $e) {
                            \Log::warning("Failed to get attributes for object", [
                                'object_key' => $objectKey,
                                'error' => $e->getMessage()
                            ]);
                        }

                        // Try to access common attributes safely
                        foreach (['name', 'surname', 'email', 'phone', 'member_no', 'birth_date', 'membership_date', 'monthly_dues', 'created_at', 'updated_at', 'application_date'] as $commonAttr) {
                            try {
                                if (isset($object->{$commonAttr})) {
                                    $attrValue = $object->{$commonAttr};
                                    $attrString = $this->convertToString($attrValue);
                                    $content = str_replace('{{ $' . $objectKey . '->' . $commonAttr . ' }}', $attrString, $content);
                                    $content = str_replace('{{$' . $objectKey . '->' . $commonAttr . '}}', $attrString, $content);
                                }
                            } catch (\Exception $e) {
                                continue;
                            }
                        }
                    }
                }
            }

            return $content;
        } catch (\Exception $e) {
            \Log::warning("Failed to replace object variables", [
                'object_key' => $objectKey,
                'object_class' => get_class($object),
                'error' => $e->getMessage()
            ]);
            return $content; // Return original content if replacement fails
        }
    }

    /**
     * Convert any value to string safely
     */
    private function convertToString($value)
    {
        try {
            if (is_string($value)) {
                return $value;
            } elseif (is_numeric($value)) {
                return (string) $value;
            } elseif (is_bool($value)) {
                return $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                return '';
            } elseif (is_array($value)) {
                return json_encode($value, JSON_UNESCAPED_UNICODE) ?: '';
            } elseif (is_object($value)) {
                // Handle objects - try to convert to JSON or get class name
                try {
                    if (method_exists($value, '__toString')) {
                        return (string) $value;
                    } elseif (method_exists($value, 'format') && $value instanceof \DateTime) {
                        return $value->format('d.m.Y H:i');
                    } elseif (method_exists($value, 'toArray')) {
                        return json_encode($value->toArray(), JSON_UNESCAPED_UNICODE) ?: '';
                    } else {
                        // For stdClass and other objects, convert to JSON
                        return json_encode($value, JSON_UNESCAPED_UNICODE) ?: get_class($value);
                    }
                } catch (\Exception $e) {
                    return get_class($value);
                }
            } else {
                return (string) $value;
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to convert value to string", [
                'value_type' => gettype($value),
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }
}
