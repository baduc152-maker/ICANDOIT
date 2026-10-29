<?php

namespace App\Support;

/**
 * Mô tả cấu trúc nội dung website để trang quản trị tự dựng biểu mẫu.
 *
 * Mỗi nhóm nội dung ('section') tương ứng một khoá trong config/center.php:
 *  - type 'group': một bộ thông tin duy nhất (thương hiệu, liên hệ).
 *  - type 'list' : danh sách lặp lại (khoá học, giảng viên, bài viết...).
 *
 * Kiểu ô nhập ('type' của field):
 *  - text | textarea | number | checkbox | select : ô nhập thông thường.
 *  - lines      : mỗi dòng là một phần tử của mảng.
 *  - paragraphs : mỗi đoạn (cách nhau một dòng trống) là một phần tử.
 *  - pairs      : mỗi dòng dạng "Tiêu đề :: Nội dung".
 */
class SiteContentSchema
{
    public const PAIR_SEPARATOR = '::';

    /** Danh sách biểu tượng dùng được cho thẻ giá trị nổi bật. */
    public const ICONS = [
        'target' => 'Mục tiêu', 'book' => 'Sách', 'users' => 'Nhóm học viên',
        'chart' => 'Biểu đồ', 'shield' => 'Cam kết', 'clock' => 'Thời gian',
        'calendar' => 'Lịch', 'timer' => 'Đồng hồ', 'layers' => 'Cấp độ',
        'award' => 'Chứng nhận', 'graduation' => 'Tốt nghiệp', 'chat' => 'Trò chuyện',
        'star' => 'Ngôi sao', 'check' => 'Dấu tích',
    ];

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function sections(): array
    {
        return [
            'brand' => [
                'label' => 'Thương hiệu',
                'summary' => 'Tên trung tâm, slogan, mô tả chung và đường dẫn logo.',
                'type' => 'group',
                'fields' => [
                    'name' => ['label' => 'Tên trung tâm', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'short' => ['label' => 'Tên viết tắt', 'type' => 'text', 'rules' => ['required', 'string', 'max:60']],
                    'tagline' => ['label' => 'Dòng mô tả dưới tên', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'slogan' => ['label' => 'Slogan', 'type' => 'text', 'rules' => ['required', 'string', 'max:160']],
                    'description' => ['label' => 'Mô tả trung tâm', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:600'],
                        'help' => 'Dùng cho phần mở đầu trang chủ, chân trang và thẻ mô tả SEO.'],
                    'founded' => ['label' => 'Năm thành lập', 'type' => 'number', 'rules' => ['required', 'integer', 'min:1900', 'max:2100']],
                    'logo_mark' => ['label' => 'Logo biểu tượng', 'type' => 'text', 'rules' => ['required', 'string', 'max:200'],
                        'help' => 'Đường dẫn tính từ thư mục public/, ví dụ images/logo-mark.png'],
                    'logo_full' => ['label' => 'Logo đầy đủ', 'type' => 'text', 'rules' => ['required', 'string', 'max:200']],
                    'logo_light' => ['label' => 'Logo cho nền tối', 'type' => 'text', 'rules' => ['required', 'string', 'max:200']],
                ],
            ],

            'contact' => [
                'label' => 'Thông tin liên hệ',
                'summary' => 'Hotline, email, địa chỉ, giờ làm việc và các kênh mạng xã hội.',
                'type' => 'group',
                'preview' => 'site.contact',
                'fields' => [
                    'hotline' => ['label' => 'Hotline hiển thị', 'type' => 'text', 'rules' => ['required', 'string', 'max:40']],
                    'hotline_href' => ['label' => 'Liên kết gọi', 'type' => 'text', 'rules' => ['required', 'string', 'max:60'],
                        'help' => 'Dạng tel:0900000000'],
                    'email' => ['label' => 'Email', 'type' => 'text', 'rules' => ['required', 'email', 'max:150']],
                    'address' => ['label' => 'Địa chỉ', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                    'map_query' => ['label' => 'Từ khoá bản đồ', 'type' => 'text', 'rules' => ['required', 'string', 'max:150'],
                        'help' => 'Dùng để nhúng Google Maps ở trang liên hệ.'],
                    'working_hours' => ['label' => 'Giờ làm việc', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'facebook' => ['label' => 'Facebook', 'type' => 'text', 'rules' => ['required', 'url', 'max:255']],
                    'youtube' => ['label' => 'YouTube', 'type' => 'text', 'rules' => ['required', 'url', 'max:255']],
                    'zalo' => ['label' => 'Zalo', 'type' => 'text', 'rules' => ['required', 'url', 'max:255']],
                    'messenger' => ['label' => 'Messenger', 'type' => 'text', 'rules' => ['required', 'url', 'max:255']],
                ],
            ],

            'apps' => [
                'label' => 'Liên kết ứng dụng',
                'summary' => 'Đường dẫn sang app giao bài tập và app giao việc của trung tâm.',
                'type' => 'list',
                'item_label' => 'Liên kết',
                'title_field' => 'label',
                'fields' => [
                    'key' => ['label' => 'Mã liên kết', 'type' => 'text', 'rules' => ['required', 'string', 'max:40', 'regex:/^[a-z0-9-]+$/'],
                        'unique' => true, 'help' => 'Chữ thường không dấu, ví dụ: homework, management.'],
                    'label' => ['label' => 'Tên hiển thị', 'type' => 'text', 'rules' => ['required', 'string', 'max:60']],
                    'url' => ['label' => 'Đường dẫn ứng dụng', 'type' => 'text', 'rules' => ['nullable', 'url', 'max:255'],
                        'help' => 'Dán link đầy đủ, ví dụ https://... Để trống thì liên kết được ẩn khỏi website.'],
                    'audience' => ['label' => 'Dành cho', 'type' => 'select', 'rules' => ['required', 'string'],
                        'options' => ['public' => 'Học viên, phụ huynh (hiện trên website)', 'internal' => 'Nhân sự trung tâm (khu vực nội bộ)']],
                    'description' => ['label' => 'Mô tả ngắn', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:300']],
                ],
            ],

            'stats' => [
                'label' => 'Con số nổi bật',
                'summary' => 'Các con số hiển thị ở trang chủ và trang giới thiệu.',
                'type' => 'list',
                'item_label' => 'Con số',
                'preview' => 'site.home',
                'fields' => [
                    'value' => ['label' => 'Con số', 'type' => 'text', 'rules' => ['required', 'string', 'max:30']],
                    'label' => ['label' => 'Chú thích', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                ],
            ],

            'values' => [
                'label' => 'Giá trị khác biệt',
                'summary' => 'Các thẻ "Vì sao chọn ICANDOIT" ở trang chủ và trang giới thiệu.',
                'type' => 'list',
                'item_label' => 'Giá trị',
                'title_field' => 'title',
                'preview' => 'site.home',
                'fields' => [
                    'icon' => ['label' => 'Biểu tượng', 'type' => 'select', 'options' => self::ICONS, 'rules' => ['required', 'string']],
                    'title' => ['label' => 'Tiêu đề', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'text' => ['label' => 'Nội dung', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:400']],
                ],
            ],

            'roadmap' => [
                'label' => 'Quy trình đào tạo',
                'summary' => 'Các bước đồng hành cùng học viên, hiển thị dạng mốc đánh số.',
                'type' => 'list',
                'item_label' => 'Bước',
                'title_field' => 'title',
                'preview' => 'site.home',
                'fields' => [
                    'step' => ['label' => 'Số thứ tự', 'type' => 'text', 'rules' => ['required', 'string', 'max:6'], 'help' => 'Ví dụ: 01'],
                    'title' => ['label' => 'Tên bước', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'text' => ['label' => 'Mô tả', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:400']],
                ],
            ],

            'courses' => [
                'label' => 'Khoá học',
                'summary' => 'Toàn bộ khoá học: thông tin lớp, đầu ra và nội dung chương trình.',
                'type' => 'list',
                'item_label' => 'Khoá học',
                'title_field' => 'name',
                'preview' => 'site.courses',
                'fields' => [
                    'name' => ['label' => 'Tên khoá học', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'slug' => ['label' => 'Đường dẫn', 'type' => 'text', 'rules' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/'],
                        'unique' => true, 'help' => 'Chỉ dùng chữ thường, số và dấu gạch ngang. Ví dụ: ielts-foundation'],
                    'subtitle' => ['label' => 'Mô tả ngắn dưới tên', 'type' => 'text', 'rules' => ['required', 'string', 'max:200']],
                    'level' => ['label' => 'Trình độ đầu vào', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'target' => ['label' => 'Mục tiêu đầu ra', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'duration' => ['label' => 'Thời lượng', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'sessions' => ['label' => 'Lịch học', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'class_size' => ['label' => 'Sĩ số', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'tuition' => ['label' => 'Học phí', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'featured' => ['label' => 'Hiển thị ở trang chủ', 'type' => 'checkbox'],
                    'summary' => ['label' => 'Giới thiệu khoá học', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:600']],
                    'for_whom' => ['label' => 'Dành cho ai', 'type' => 'lines', 'rules' => ['required', 'string', 'max:1500'],
                        'help' => 'Mỗi dòng là một đối tượng học viên.'],
                    'outcomes' => ['label' => 'Kết quả đầu ra', 'type' => 'lines', 'rules' => ['required', 'string', 'max:1500'],
                        'help' => 'Mỗi dòng là một kết quả.'],
                    'curriculum' => ['label' => 'Nội dung chương trình', 'type' => 'pairs', 'rules' => ['required', 'string', 'max:3000'],
                        'help' => 'Mỗi dòng một phần, dạng: Tên phần :: Mô tả phần.'],
                ],
            ],

            'teachers' => [
                'label' => 'Giảng viên',
                'summary' => 'Hồ sơ đội ngũ giảng viên hiển thị ở trang chủ và trang giảng viên.',
                'type' => 'list',
                'item_label' => 'Giảng viên',
                'title_field' => 'name',
                'preview' => 'site.teachers',
                'fields' => [
                    'name' => ['label' => 'Họ và tên', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'role' => ['label' => 'Vị trí', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'credentials' => ['label' => 'Bằng cấp, chứng chỉ', 'type' => 'text', 'rules' => ['required', 'string', 'max:200']],
                    'focus' => ['label' => 'Phụ trách', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'bio' => ['label' => 'Giới thiệu', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:600']],
                ],
            ],

            'testimonials' => [
                'label' => 'Cảm nhận học viên',
                'summary' => 'Trích dẫn của học viên và phụ huynh hiển thị ở trang chủ.',
                'type' => 'list',
                'item_label' => 'Cảm nhận',
                'title_field' => 'name',
                'preview' => 'site.home',
                'fields' => [
                    'name' => ['label' => 'Người chia sẻ', 'type' => 'text', 'rules' => ['required', 'string', 'max:120']],
                    'detail' => ['label' => 'Kết quả / lớp học', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'quote' => ['label' => 'Nội dung chia sẻ', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:600']],
                ],
            ],

            'schedule' => [
                'label' => 'Lịch khai giảng',
                'summary' => 'Bảng các lớp sắp mở ở trang chủ và trang lịch khai giảng.',
                'type' => 'list',
                'item_label' => 'Lớp',
                'title_field' => 'course',
                'preview' => 'site.schedule',
                'fields' => [
                    'course' => ['label' => 'Khoá học', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'code' => ['label' => 'Mã lớp', 'type' => 'text', 'rules' => ['required', 'string', 'max:40']],
                    'open_at' => ['label' => 'Ngày khai giảng', 'type' => 'text', 'rules' => ['required', 'string', 'max:40'], 'help' => 'Ví dụ: 05/09'],
                    'time' => ['label' => 'Lịch học', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                    'seats' => ['label' => 'Chỗ trống', 'type' => 'text', 'rules' => ['required', 'string', 'max:60']],
                ],
            ],

            'posts' => [
                'label' => 'Bài viết cẩm nang',
                'summary' => 'Bài viết học thuật hiển thị ở trang chủ và mục cẩm nang.',
                'type' => 'list',
                'item_label' => 'Bài viết',
                'title_field' => 'title',
                'preview' => 'site.posts',
                'fields' => [
                    'title' => ['label' => 'Tiêu đề', 'type' => 'text', 'rules' => ['required', 'string', 'max:200']],
                    'slug' => ['label' => 'Đường dẫn', 'type' => 'text', 'rules' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/'],
                        'unique' => true, 'help' => 'Chỉ dùng chữ thường, số và dấu gạch ngang.'],
                    'date' => ['label' => 'Ngày đăng', 'type' => 'text', 'rules' => ['required', 'string', 'max:30'], 'help' => 'Ví dụ: 12/06/2026'],
                    'category' => ['label' => 'Chuyên mục', 'type' => 'text', 'rules' => ['required', 'string', 'max:80']],
                    'excerpt' => ['label' => 'Tóm tắt', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                    'body' => ['label' => 'Nội dung bài viết', 'type' => 'paragraphs', 'rules' => ['required', 'string', 'max:8000'],
                        'help' => 'Các đoạn văn cách nhau bằng một dòng trống.'],
                ],
            ],

            'faqs' => [
                'label' => 'Câu hỏi thường gặp',
                'summary' => 'Mục hỏi đáp ở trang chủ, trang khoá học và trang liên hệ.',
                'type' => 'list',
                'item_label' => 'Câu hỏi',
                'title_field' => 'q',
                'preview' => 'site.home',
                'fields' => [
                    'q' => ['label' => 'Câu hỏi', 'type' => 'text', 'rules' => ['required', 'string', 'max:250']],
                    'a' => ['label' => 'Câu trả lời', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:800']],
                ],
            ],

            'consult_topics' => [
                'label' => 'Lựa chọn trong form tư vấn',
                'summary' => 'Các mục thêm vào danh sách "Khoá học quan tâm" ngoài danh mục khoá học.',
                'type' => 'list',
                'item_label' => 'Lựa chọn',
                'scalar' => true,
                'fields' => [
                    'value' => ['label' => 'Nội dung lựa chọn', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
                ],
            ],
        ];
    }

    public static function has(string $section): bool
    {
        return array_key_exists($section, self::sections());
    }

    /**
     * @return array<string, mixed>
     */
    public static function section(string $section): array
    {
        return self::sections()[$section] ?? [];
    }
}
