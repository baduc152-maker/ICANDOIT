<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteContentManager;
use App\Support\ContentField;
use App\Support\SiteContentSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trang quản trị nội dung website: nhập mới và chỉnh sửa mọi thông tin hiển thị
 * trên website trung tâm mà không cần sửa mã nguồn.
 */
class ContentController extends Controller
{
    public function __construct(private readonly SiteContentManager $content)
    {
    }

    /**
     * Danh sách các nhóm nội dung có thể chỉnh sửa.
     */
    public function index(): View
    {
        $sections = [];

        foreach (SiteContentSchema::sections() as $key => $schema) {
            $value = $this->content->section($key);

            $sections[$key] = [
                'schema' => $schema,
                'count' => $schema['type'] === 'list' ? count((array) $value) : count($schema['fields']),
                'customised' => $this->content->isCustomised($key),
                'record' => $this->content->isCustomised($key) ? $this->content->lastUpdated($key) : null,
            ];
        }

        return view('admin.content.index', ['sections' => $sections]);
    }

    /**
     * Biểu mẫu chỉnh sửa một nhóm nội dung.
     */
    public function edit(string $section): View
    {
        $schema = $this->schema($section);
        $value = $this->content->section($section);

        return view($schema['type'] === 'group' ? 'admin.content.group' : 'admin.content.list', [
            'section' => $section,
            'schema' => $schema,
            'fields' => $schema['type'] === 'group' ? $this->groupToInput($schema, (array) $value) : [],
            'items' => $schema['type'] === 'list' ? $this->listToInput($schema, (array) $value) : [],
            'customised' => $this->content->isCustomised($section),
            'record' => $this->content->lastUpdated($section),
        ]);
    }

    /**
     * Lưu nội dung đã chỉnh sửa.
     */
    public function update(Request $request, string $section): RedirectResponse
    {
        $schema = $this->schema($section);

        $payload = $schema['type'] === 'group'
            ? $this->validateGroup($request, $schema)
            : $this->validateList($request, $schema);

        $this->content->save($section, $payload, $request->user()?->id);

        return redirect()
            ->route('admin.content.edit', $section)
            ->with('status', 'Đã lưu nội dung "'.$schema['label'].'". Website đã hiển thị bản mới.');
    }

    /**
     * Khôi phục nhóm nội dung về bản mặc định trong mã nguồn.
     */
    public function reset(string $section): RedirectResponse
    {
        $schema = $this->schema($section);

        $this->content->reset($section);

        return redirect()
            ->route('admin.content.edit', $section)
            ->with('status', 'Đã khôi phục nội dung mặc định cho "'.$schema['label'].'".');
    }

    /**
     * @return array<string, mixed>
     */
    private function schema(string $section): array
    {
        if (! SiteContentSchema::has($section)) {
            throw new NotFoundHttpException('Không tìm thấy nhóm nội dung.');
        }

        return SiteContentSchema::section($section);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateGroup(Request $request, array $schema): array
    {
        $rules = [];
        $attributes = [];

        foreach ($schema['fields'] as $key => $field) {
            $rules["fields.$key"] = $this->rulesFor($field);
            $attributes["fields.$key"] = mb_strtolower($field['label']);
        }

        $data = $request->validate($rules, [], $attributes)['fields'] ?? [];

        $payload = [];
        foreach ($schema['fields'] as $key => $field) {
            $payload[$key] = ContentField::fromInput($field, $data[$key] ?? null);
        }

        return $payload;
    }

    /**
     * @return array<int, mixed>
     */
    private function validateList(Request $request, array $schema): array
    {
        $rules = ['items' => ['array', 'max:60']];
        $attributes = [];

        if (($schema['scalar'] ?? false) === false) {
            $rules['items'][] = 'min:1';
        }

        foreach ($schema['fields'] as $key => $field) {
            $rules["items.*.$key"] = $this->rulesFor($field);
            $attributes["items.*.$key"] = mb_strtolower($field['label']);
        }

        $rows = $request->validate($rules, [
            'items.min' => 'Cần giữ lại ít nhất một mục.',
        ], $attributes)['items'] ?? [];

        $payload = [];

        foreach ($rows as $row) {
            if ($schema['scalar'] ?? false) {
                $payload[] = ContentField::fromInput($schema['fields']['value'], $row['value'] ?? '');

                continue;
            }

            $item = [];
            foreach ($schema['fields'] as $key => $field) {
                $item[$key] = ContentField::fromInput($field, $row[$key] ?? null);
            }
            $payload[] = $item;
        }

        $this->assertUniqueFields($schema, $payload);

        return $payload;
    }

    /**
     * Các trường đánh dấu 'unique' (đường dẫn khoá học, bài viết) không được trùng.
     */
    private function assertUniqueFields(array $schema, array $payload): void
    {
        foreach ($schema['fields'] as $key => $field) {
            if (! ($field['unique'] ?? false)) {
                continue;
            }

            $values = array_column($payload, $key);
            $duplicates = array_keys(array_filter(array_count_values($values), fn ($count) => $count > 1));

            if ($duplicates !== []) {
                throw ValidationException::withMessages([
                    'items' => sprintf(
                        'Giá trị "%s" của ô %s bị trùng. Mỗi mục cần một giá trị riêng.',
                        implode(', ', $duplicates),
                        mb_strtolower($field['label'])
                    ),
                ]);
            }
        }
    }

    /**
     * @return array<int, mixed>
     */
    private function rulesFor(array $field): array
    {
        if ($field['type'] === 'checkbox') {
            return ['nullable', 'boolean'];
        }

        if ($field['type'] === 'select') {
            return ['required', 'string', 'in:'.implode(',', array_keys($field['options'] ?? []))];
        }

        return $field['rules'] ?? ['nullable', 'string'];
    }

    /**
     * @return array<string, mixed>
     */
    private function groupToInput(array $schema, array $value): array
    {
        $fields = [];

        foreach ($schema['fields'] as $key => $field) {
            $fields[$key] = ContentField::toInput($field, $value[$key] ?? null);
        }

        return $fields;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listToInput(array $schema, array $value): array
    {
        $items = [];

        foreach ($value as $row) {
            if ($schema['scalar'] ?? false) {
                $items[] = ['value' => (string) $row];

                continue;
            }

            $item = [];
            foreach ($schema['fields'] as $key => $field) {
                $item[$key] = ContentField::toInput($field, is_array($row) ? ($row[$key] ?? null) : null);
            }
            $items[] = $item;
        }

        return $items;
    }
}
