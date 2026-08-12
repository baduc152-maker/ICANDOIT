<?php

/*
|--------------------------------------------------------------------------
| Nội dung website trung tâm ICANDOIT ACADEMIC ENGLISH
|--------------------------------------------------------------------------
|
| Toàn bộ nội dung hiển thị trên website marketing được khai báo tại đây để
| người quản trị có thể cập nhật mà không phải sửa giao diện. Các thông tin
| liên hệ, học phí, hồ sơ giảng viên và cảm nhận học viên hiện đang là DỮ
| LIỆU MẪU — vui lòng thay bằng thông tin thật của trung tâm trước khi phát
| hành (xem mục "Cập nhật nội dung" trong README).
|
*/

return [

    'brand' => [
        'name' => 'I CAN DO IT ENGLISH',
        'short' => 'ICANDOIT',
        'tagline' => 'Tiếng Anh Học Thuật',
        'slogan' => 'Học thuật vững vàng – Tự tin bứt phá',
        'description' => 'Trung tâm đào tạo tiếng Anh học thuật chuyên sâu: IELTS, Academic Writing '
            .'và tiếng Anh nền tảng cho học sinh – sinh viên, với lộ trình cá nhân hoá và cam kết đầu ra.',
        'founded' => 2016,

        // Đường dẫn logo trong thư mục public/. Muốn dùng file gốc của trung tâm,
        // chỉ cần chép ảnh vào public/images/ rồi đổi hai giá trị dưới đây.
        'logo_mark' => 'images/logo-mark.svg',   // chỉ biểu tượng (header, favicon)
        'logo_full' => 'images/logo.svg',        // logo đầy đủ kèm chữ
        'logo_light' => 'images/logo-mark-light.svg', // biểu tượng cho nền tối (footer)
    ],

    'contact' => [
        'hotline' => '0900 000 000',
        'hotline_href' => 'tel:0900000000',
        'email' => 'lienhe@icandoit.edu.vn',
        'address' => 'Số 00, Đường ABC, Phường XYZ, Thành phố ...',
        'map_query' => 'ICANDOIT Academic English',
        'working_hours' => 'Thứ 2 – Chủ nhật: 08:00 – 21:00',
        'facebook' => 'https://www.facebook.com/',
        'youtube' => 'https://www.youtube.com/',
        'zalo' => 'https://zalo.me/0900000000',
        'messenger' => 'https://m.me/',
    ],

    // Con số nổi bật hiển thị ở trang chủ.
    'stats' => [
        ['value' => '5.000+', 'label' => 'Học viên đã đồng hành'],
        ['value' => '92%', 'label' => 'Đạt hoặc vượt mục tiêu đầu ra'],
        ['value' => '8.0+', 'label' => 'Điểm IELTS trung bình của giảng viên'],
        ['value' => '10', 'label' => 'Năm kinh nghiệm đào tạo học thuật'],
    ],

    // Giá trị khác biệt (USP).
    'values' => [
        [
            'icon' => 'target',
            'title' => 'Lộ trình cá nhân hoá',
            'text' => 'Mỗi học viên được kiểm tra đầu vào 4 kỹ năng, phân tích điểm mạnh – điểm yếu '
                .'và nhận lộ trình riêng theo đúng mục tiêu, thời hạn thi.',
        ],
        [
            'icon' => 'book',
            'title' => 'Giáo trình học thuật chuẩn quốc tế',
            'text' => 'Hệ thống tài liệu biên soạn nội bộ kết hợp Cambridge, Oxford và ngân hàng đề '
                .'cập nhật liên tục theo xu hướng ra đề mới nhất.',
        ],
        [
            'icon' => 'users',
            'title' => 'Lớp nhỏ 8 – 12 học viên',
            'text' => 'Sĩ số giới hạn để giảng viên theo sát từng bạn, chữa bài Writing – Speaking '
                .'chi tiết đến từng câu, từng lỗi.',
        ],
        [
            'icon' => 'chart',
            'title' => 'Đo lường tiến độ minh bạch',
            'text' => 'Kiểm tra định kỳ hai tuần một lần, báo cáo tiến độ gửi phụ huynh và học viên '
                .'kèm khuyến nghị điều chỉnh cụ thể.',
        ],
        [
            'icon' => 'shield',
            'title' => 'Cam kết đầu ra bằng văn bản',
            'text' => 'Học viên đi học đủ và hoàn thành bài tập nhưng chưa đạt mục tiêu sẽ được học '
                .'lại miễn phí đến khi đạt.',
        ],
        [
            'icon' => 'clock',
            'title' => 'Hỗ trợ ngoài giờ 1:1',
            'text' => 'Phòng tự học có trợ giảng trực, nhóm chữa bài online và buổi Speaking Club '
                .'miễn phí hằng tuần.',
        ],
    ],

    // Lộ trình đào tạo tổng quát.
    'roadmap' => [
        [
            'step' => '01',
            'title' => 'Kiểm tra đầu vào miễn phí',
            'text' => 'Bài test 4 kỹ năng và phỏng vấn trực tiếp giúp xác định chính xác trình độ '
                .'hiện tại cùng mục tiêu thực tế.',
        ],
        [
            'step' => '02',
            'title' => 'Thiết kế lộ trình riêng',
            'text' => 'Học thuật trưởng tư vấn khoá học, thời lượng và mốc điểm cần đạt cho từng '
                .'giai đoạn của học viên.',
        ],
        [
            'step' => '03',
            'title' => 'Học – luyện – chữa bài',
            'text' => 'Mỗi buổi học gồm đầu vào kiến thức, luyện đề có kiểm soát thời gian và chữa '
                .'bài chi tiết theo tiêu chí chấm chính thức.',
        ],
        [
            'step' => '04',
            'title' => 'Kiểm tra định kỳ & phản hồi',
            'text' => 'Thi thử theo định dạng thật, đối chiếu tiến độ với lộ trình và điều chỉnh kế '
                .'hoạch khi cần.',
        ],
        [
            'step' => '05',
            'title' => 'Thi thật & đồng hành sau khoá',
            'text' => 'Hướng dẫn đăng ký thi, luyện tăng tốc giai đoạn cuối và hỗ trợ hồ sơ du học, '
                .'học bổng sau khi có kết quả.',
        ],
    ],

    // Danh mục khoá học.
    'courses' => [
        [
            'slug' => 'ielts-foundation',
            'name' => 'IELTS Foundation',
            'subtitle' => 'Xây nền tảng học thuật từ con số 0',
            'level' => 'Mất gốc – IELTS 4.0',
            'target' => 'Đầu ra IELTS 4.5 – 5.0',
            'duration' => '3 tháng · 36 buổi',
            'sessions' => '3 buổi/tuần · 120 phút/buổi',
            'class_size' => '10 – 12 học viên',
            'tuition' => 'Liên hệ để nhận báo giá',
            'featured' => true,
            'summary' => 'Khoá học tái thiết nền tảng ngữ âm – từ vựng – ngữ pháp học thuật, giúp '
                .'học viên mất gốc làm quen với định dạng IELTS một cách có hệ thống.',
            'for_whom' => [
                'Học viên mất gốc hoặc chưa từng tiếp xúc với IELTS',
                'Học sinh lớp 8 – 12 muốn chuẩn bị sớm cho lộ trình học thuật',
                'Người đi làm cần khởi động lại tiếng Anh sau thời gian gián đoạn',
            ],
            'outcomes' => [
                'Phát âm chuẩn IPA, nghe hiểu hội thoại tốc độ chậm và trung bình',
                'Nắm 1.200 từ vựng học thuật theo 10 chủ đề thi phổ biến',
                'Viết đúng câu phức, đoạn văn 100 – 120 từ mạch lạc',
                'Làm quen đầy đủ 4 kỹ năng theo định dạng đề IELTS',
            ],
            'curriculum' => [
                ['title' => 'Giai đoạn 1 · Ngữ âm & phản xạ', 'text' => 'Bảng IPA, trọng âm, nối âm, luyện nghe chép chính tả và phản xạ hội thoại cơ bản.'],
                ['title' => 'Giai đoạn 2 · Ngữ pháp học thuật', 'text' => 'Hệ thống thì, mệnh đề quan hệ, câu bị động và cấu trúc so sánh dùng trong bài viết học thuật.'],
                ['title' => 'Giai đoạn 3 · Làm quen IELTS', 'text' => 'Giới thiệu 4 kỹ năng, chiến lược làm bài cơ bản và bài thi thử đầu tiên có chữa chi tiết.'],
            ],
        ],
        [
            'slug' => 'ielts-intermediate',
            'name' => 'IELTS 5.5 – 6.5',
            'subtitle' => 'Chinh phục mốc điểm xét tuyển đại học',
            'level' => 'IELTS 4.5 – 5.5',
            'target' => 'Đầu ra IELTS 6.0 – 6.5',
            'duration' => '3 tháng · 36 buổi',
            'sessions' => '3 buổi/tuần · 120 phút/buổi',
            'class_size' => '8 – 12 học viên',
            'tuition' => 'Liên hệ để nhận báo giá',
            'featured' => true,
            'summary' => 'Khoá học trọng tâm cho học sinh – sinh viên cần chứng chỉ xét tuyển, tập '
                .'trung vào chiến lược làm bài và kỹ năng viết luận Task 1 – Task 2.',
            'for_whom' => [
                'Học viên đã có nền tảng 4.5 – 5.5 muốn đạt mốc xét tuyển đại học',
                'Sinh viên cần chứng chỉ chuẩn đầu ra ngoại ngữ',
                'Học viên chuẩn bị hồ sơ du học bậc cử nhân',
            ],
            'outcomes' => [
                'Xử lý trọn vẹn 40 câu Listening và Reading trong thời gian quy định',
                'Viết Task 1 và Task 2 đủ ý, đúng bố cục, đạt band 6.0+ về Task Response',
                'Nói trôi chảy 2 phút Part 2 với dàn ý rõ ràng và từ vựng chủ đề',
                'Hoàn thành tối thiểu 6 bài thi thử full test có chữa 1:1',
            ],
            'curriculum' => [
                ['title' => 'Module 1 · Listening & Reading chiến lược', 'text' => 'Kỹ thuật paraphrase, scanning, skimming và quản lý thời gian cho từng dạng câu hỏi.'],
                ['title' => 'Module 2 · Writing Task 1 & Task 2', 'text' => 'Phân tích biểu đồ, xây dựng luận điểm, viết mở – thân – kết theo tiêu chí chấm chính thức.'],
                ['title' => 'Module 3 · Speaking phản xạ', 'text' => 'Luyện Part 1-2-3 theo bộ đề dự đoán quý, ghi âm và nhận xét phát âm từng buổi.'],
                ['title' => 'Module 4 · Tăng tốc trước thi', 'text' => 'Thi thử theo lịch thật, phân tích lỗi sai lặp lại và kế hoạch ôn 2 tuần cuối.'],
            ],
        ],
        [
            'slug' => 'ielts-advanced',
            'name' => 'IELTS 7.0+ Advanced',
            'subtitle' => 'Bứt phá band điểm cho hồ sơ du học – học bổng',
            'level' => 'IELTS 6.0 – 6.5',
            'target' => 'Đầu ra IELTS 7.0 – 7.5+',
            'duration' => '2,5 tháng · 30 buổi',
            'sessions' => '3 buổi/tuần · 120 phút/buổi',
            'class_size' => '6 – 8 học viên',
            'tuition' => 'Liên hệ để nhận báo giá',
            'featured' => true,
            'summary' => 'Lớp chuyên sâu cho học viên hướng tới band 7.0+, nhấn mạnh tư duy phản '
                .'biện, độ chính xác ngôn ngữ và chiều sâu lập luận học thuật.',
            'for_whom' => [
                'Học viên đã đạt 6.0 – 6.5 muốn nâng band cho hồ sơ du học, học bổng',
                'Giáo viên, người đi làm cần chứng chỉ trình độ cao',
                'Học viên chuẩn bị thi các chương trình sau đại học',
            ],
            'outcomes' => [
                'Lập luận hai chiều, phản biện sắc bén trong Writing Task 2',
                'Sử dụng linh hoạt collocation và cấu trúc phức tạp một cách tự nhiên',
                'Nghe hiểu tốt giọng bản xứ tốc độ nhanh trong Section 3 – 4',
                'Đạt độ chính xác ngữ pháp trên 90% trong bài viết cuối khoá',
            ],
            'curriculum' => [
                ['title' => 'Module 1 · Tư duy phản biện học thuật', 'text' => 'Phân tích đề, xây dựng luận điểm đa chiều và chọn dẫn chứng thuyết phục.'],
                ['title' => 'Module 2 · Độ chính xác ngôn ngữ', 'text' => 'Nâng cấp lexical resource, sửa lỗi ngữ pháp tinh vi và kiểm soát văn phong trang trọng.'],
                ['title' => 'Module 3 · Speaking band 7+', 'text' => 'Luyện ý tưởng chiều sâu, ngữ điệu tự nhiên và xử lý câu hỏi trừu tượng Part 3.'],
                ['title' => 'Module 4 · Mock test chuyên sâu', 'text' => 'Thi thử hằng tuần, chữa bài 1:1 và phân tích phổ điểm từng tiêu chí.'],
            ],
        ],
        [
            'slug' => 'pre-ielts-teens',
            'name' => 'Pre-IELTS cho học sinh THCS',
            'subtitle' => 'Chuẩn bị sớm – vào cấp 3 vững vàng',
            'level' => 'Học sinh lớp 6 – 9',
            'target' => 'Nền tảng vào lớp chuyên Anh & IELTS 5.0+',
            'duration' => '4 tháng · 32 buổi',
            'sessions' => '2 buổi/tuần · 105 phút/buổi',
            'class_size' => '10 – 14 học viên',
            'tuition' => 'Liên hệ để nhận báo giá',
            'featured' => false,
            'summary' => 'Chương trình dành riêng cho lứa tuổi THCS, kết hợp kiến thức chương trình '
                .'phổ thông với kỹ năng học thuật để chuẩn bị cho kỳ thi vào 10 và IELTS.',
            'for_whom' => [
                'Học sinh lớp 6 – 9 muốn xây nền tảng học thuật sớm',
                'Học sinh ôn thi vào lớp 10 chuyên Anh',
                'Phụ huynh mong con có lộ trình dài hạn đến IELTS',
            ],
            'outcomes' => [
                'Vốn từ vựng học thuật 1.500 từ theo chủ đề quen thuộc',
                'Kỹ năng đọc hiểu và tóm tắt văn bản 400 – 600 từ',
                'Viết đoạn văn học thuật 150 từ có bố cục rõ ràng',
                'Thói quen tự học và ghi chép theo phương pháp Cornell',
            ],
            'curriculum' => [
                ['title' => 'Học kỳ 1 · Nền tảng ngôn ngữ', 'text' => 'Ngữ âm, ngữ pháp trọng tâm và từ vựng theo chủ đề gần gũi với lứa tuổi.'],
                ['title' => 'Học kỳ 2 · Kỹ năng học thuật', 'text' => 'Đọc hiểu, ghi chú, thuyết trình ngắn và viết đoạn văn theo cấu trúc PEEL.'],
                ['title' => 'Chuyên đề · Thi vào 10', 'text' => 'Ôn tập dạng bài thi chuyên Anh và luyện đề các năm gần đây.'],
            ],
        ],
        [
            'slug' => 'academic-writing',
            'name' => 'Academic Writing & Research English',
            'subtitle' => 'Viết luận – viết báo cáo chuẩn học thuật',
            'level' => 'IELTS 6.0+ hoặc tương đương',
            'target' => 'Viết luận, tiểu luận, báo cáo nghiên cứu',
            'duration' => '2 tháng · 16 buổi',
            'sessions' => '2 buổi/tuần · 120 phút/buổi',
            'class_size' => '6 – 10 học viên',
            'tuition' => 'Liên hệ để nhận báo giá',
            'featured' => true,
            'summary' => 'Khoá chuyên biệt cho sinh viên, học viên cao học và người làm nghiên cứu '
                .'cần viết tiểu luận, báo cáo và bài đăng bằng tiếng Anh học thuật.',
            'for_whom' => [
                'Sinh viên chương trình tiên tiến, liên kết quốc tế',
                'Học viên cao học, nghiên cứu sinh cần viết luận văn tiếng Anh',
                'Người đi làm phải viết báo cáo, đề xuất bằng tiếng Anh',
            ],
            'outcomes' => [
                'Nắm cấu trúc IMRaD và cách viết abstract, literature review',
                'Trích dẫn đúng chuẩn APA/Harvard và tránh đạo văn',
                'Sử dụng văn phong khách quan, hedging phù hợp học thuật',
                'Hoàn thiện một bài viết học thuật 1.500 từ có phản hồi của giảng viên',
            ],
            'curriculum' => [
                ['title' => 'Chuyên đề 1 · Văn phong học thuật', 'text' => 'Tính khách quan, hedging, nominalisation và cohesion trong văn bản khoa học.'],
                ['title' => 'Chuyên đề 2 · Cấu trúc bài viết', 'text' => 'Từ đề cương đến bản thảo: introduction, method, results, discussion.'],
                ['title' => 'Chuyên đề 3 · Trích dẫn & liêm chính học thuật', 'text' => 'Paraphrase, summary, quản lý tài liệu tham khảo và công cụ hỗ trợ.'],
            ],
        ],
        [
            'slug' => 'ielts-1-kem-1',
            'name' => 'IELTS 1 kèm 1',
            'subtitle' => 'Tối ưu tốc độ tiến bộ theo lịch riêng',
            'level' => 'Mọi trình độ',
            'target' => 'Theo mục tiêu cá nhân',
            'duration' => 'Linh hoạt theo gói 10 – 30 buổi',
            'sessions' => 'Học viên tự chọn lịch',
            'class_size' => '1 giảng viên – 1 học viên',
            'tuition' => 'Liên hệ để nhận báo giá',
            'featured' => false,
            'summary' => 'Hình thức kèm riêng dành cho học viên cần tiến bộ nhanh, có lịch bận hoặc '
                .'muốn tập trung xử lý một kỹ năng còn yếu.',
            'for_whom' => [
                'Học viên có deadline thi gấp',
                'Người đi làm với lịch trình không cố định',
                'Học viên cần chữa sâu một kỹ năng (thường là Writing hoặc Speaking)',
            ],
            'outcomes' => [
                'Giáo án thiết kế riêng sau buổi kiểm tra đầu vào',
                'Chữa bài chi tiết 100% bài tập đã nộp',
                'Báo cáo tiến độ sau mỗi 5 buổi học',
                'Linh hoạt đổi lịch trước 24 giờ',
            ],
            'curriculum' => [
                ['title' => 'Buổi 1 · Đánh giá & lập kế hoạch', 'text' => 'Kiểm tra 4 kỹ năng, xác định điểm nghẽn và thống nhất mục tiêu từng giai đoạn.'],
                ['title' => 'Giai đoạn luyện tập', 'text' => 'Tập trung vào kỹ năng yếu nhất, xen kẽ luyện đề tổng hợp.'],
                ['title' => 'Giai đoạn tăng tốc', 'text' => 'Mock test và tinh chỉnh chiến lược làm bài trước ngày thi.'],
            ],
        ],
    ],

    // Đội ngũ giảng viên (dữ liệu mẫu).
    'teachers' => [
        [
            'name' => 'Nguyễn Minh Anh',
            'role' => 'Học thuật trưởng · IELTS 8.5',
            'credentials' => 'Thạc sĩ Ngôn ngữ Anh · Chứng chỉ TESOL',
            'focus' => 'Writing · Reading',
            'bio' => 'Hơn 10 năm giảng dạy IELTS học thuật, trực tiếp xây dựng bộ giáo trình nội bộ '
                .'và hệ thống tiêu chí chữa bài Writing của trung tâm.',
        ],
        [
            'name' => 'Trần Quốc Bảo',
            'role' => 'Giảng viên cao cấp · IELTS 8.0',
            'credentials' => 'Cử nhân Sư phạm Anh · Chứng chỉ CELTA',
            'focus' => 'Speaking · Listening',
            'bio' => 'Chuyên luyện phản xạ và phát âm, phụ trách Speaking Club hằng tuần cùng các '
                .'lớp mục tiêu 7.0+.',
        ],
        [
            'name' => 'Lê Thu Hà',
            'role' => 'Giảng viên · IELTS 8.0',
            'credentials' => 'Cử nhân Ngôn ngữ Anh · TKT Module 1-3',
            'focus' => 'Foundation · Pre-IELTS',
            'bio' => 'Nhiều năm đồng hành cùng học viên mất gốc và học sinh THCS, nổi bật với cách '
                .'truyền đạt ngắn gọn, dễ hiểu.',
        ],
        [
            'name' => 'Phạm Gia Huy',
            'role' => 'Giảng viên Academic Writing',
            'credentials' => 'Thạc sĩ Giáo dục (Anh Quốc)',
            'focus' => 'Academic Writing · EAP',
            'bio' => 'Hướng dẫn sinh viên và học viên cao học viết tiểu luận, báo cáo nghiên cứu '
                .'theo chuẩn quốc tế.',
        ],
    ],

    // Cảm nhận học viên (dữ liệu mẫu).
    'testimonials' => [
        [
            'name' => 'Vũ Hoàng Nam',
            'detail' => 'IELTS 7.5 · Lớp Advanced',
            'quote' => 'Điều em thấy khác biệt nhất là bài Writing nào cũng được chữa tay từng câu. '
                .'Sau 3 tháng em tăng từ 6.0 lên 7.5 và đủ điều kiện nộp học bổng.',
        ],
        [
            'name' => 'Đặng Khánh Linh',
            'detail' => 'IELTS 6.5 · Lớp 5.5 – 6.5',
            'quote' => 'Em vào lớp khi mới 4.5 và khá sợ Speaking. Thầy cô sửa phát âm rất kỹ, mỗi '
                .'buổi đều có ghi âm nên em tự thấy mình tiến bộ.',
        ],
        [
            'name' => 'Chị Nguyễn Thanh Thảo',
            'detail' => 'Phụ huynh học viên lớp Pre-IELTS',
            'quote' => 'Trung tâm gửi báo cáo tiến độ đều đặn nên gia đình luôn nắm được con đang '
                .'học đến đâu, cần hỗ trợ gì thêm.',
        ],
        [
            'name' => 'Ngô Đức Trung',
            'detail' => 'Academic Writing · Sinh viên năm 4',
            'quote' => 'Khoá viết học thuật giúp em hoàn thành khoá luận tiếng Anh đúng chuẩn trích '
                .'dẫn, điều mà trước đó em hoàn toàn mơ hồ.',
        ],
        [
            'name' => 'Bùi Phương Mai',
            'detail' => 'IELTS 7.0 · Lớp 1 kèm 1',
            'quote' => 'Em đi làm nên chỉ học buổi tối được. Lịch linh hoạt và giáo án riêng giúp em '
                .'đạt 7.0 chỉ sau 20 buổi.',
        ],
        [
            'name' => 'Hoàng Bảo Long',
            'detail' => 'IELTS 5.5 · Lớp Foundation',
            'quote' => 'Từ mất gốc, em học lại phát âm từ đầu. Lớp ít người nên thầy cô gọi phát biểu '
                .'liên tục, không ai bị bỏ lại.',
        ],
    ],

    // Lịch khai giảng (dữ liệu mẫu).
    'schedule' => [
        ['course' => 'IELTS Foundation', 'code' => 'FDN-01', 'open_at' => '05/09', 'time' => 'Thứ 2 - 4 - 6 · 18:00 – 20:00', 'seats' => 'Còn 4 chỗ'],
        ['course' => 'IELTS 5.5 – 6.5', 'code' => 'INT-03', 'open_at' => '09/09', 'time' => 'Thứ 3 - 5 - 7 · 18:00 – 20:00', 'seats' => 'Còn 2 chỗ'],
        ['course' => 'IELTS 7.0+ Advanced', 'code' => 'ADV-02', 'open_at' => '12/09', 'time' => 'Thứ 2 - 4 - 6 · 19:30 – 21:30', 'seats' => 'Còn 3 chỗ'],
        ['course' => 'Pre-IELTS cho học sinh THCS', 'code' => 'PRE-05', 'open_at' => '14/09', 'time' => 'Thứ 7 - Chủ nhật · 14:00 – 15:45', 'seats' => 'Còn 6 chỗ'],
        ['course' => 'Academic Writing & Research English', 'code' => 'AWR-01', 'open_at' => '16/09', 'time' => 'Thứ 3 - 5 · 19:30 – 21:30', 'seats' => 'Còn 5 chỗ'],
    ],

    // Tin tức / cẩm nang học thuật (dữ liệu mẫu).
    'posts' => [
        [
            'slug' => 'lo-trinh-tu-mat-goc-den-ielts-6-5',
            'title' => 'Lộ trình từ mất gốc đến IELTS 6.5 trong 9 tháng',
            'date' => '12/06/2026',
            'category' => 'Lộ trình học',
            'excerpt' => 'Ba giai đoạn rõ ràng, mốc kiểm tra cụ thể và những sai lầm khiến người học '
                .'mất gốc dễ bỏ cuộc ngay tháng đầu tiên.',
            'body' => [
                'Rất nhiều học viên bắt đầu với câu hỏi: "Mất gốc thì bao lâu mới thi được IELTS?". '
                    .'Câu trả lời phụ thuộc vào cường độ học, nhưng với 3 buổi/tuần đều đặn, 9 tháng '
                    .'là khung thời gian thực tế để đi từ con số 0 đến band 6.5.',
                'Giai đoạn 1 (tháng 1 – 3) dành trọn cho ngữ âm, ngữ pháp lõi và 1.200 từ vựng học '
                    .'thuật. Đây là giai đoạn quyết định: người học bỏ qua phát âm sẽ gặp trần kỹ '
                    .'năng Listening và Speaking ở các giai đoạn sau.',
                'Giai đoạn 2 (tháng 4 – 6) chuyển sang chiến lược làm bài từng dạng câu hỏi, viết '
                    .'Task 1 và Task 2 ở mức đủ ý, đồng thời hình thành thói quen luyện đề có kiểm '
                    .'soát thời gian.',
                'Giai đoạn 3 (tháng 7 – 9) là giai đoạn tăng tốc: thi thử hằng tuần, phân tích lỗi '
                    .'lặp lại và tinh chỉnh chiến thuật phòng thi. Học viên nên đăng ký thi thật '
                    .'ngay khi điểm mock ổn định ở mức mục tiêu trong ba lần liên tiếp.',
            ],
        ],
        [
            'slug' => 'nam-loi-writing-task-2-thuong-gap',
            'title' => 'Năm lỗi Writing Task 2 khiến bài viết mãi dừng ở band 6.0',
            'date' => '28/05/2026',
            'category' => 'Kỹ năng Writing',
            'excerpt' => 'Không phải từ vựng khó mới nâng band. Phần lớn bài viết 6.0 mắc đúng năm '
                .'lỗi cấu trúc và lập luận sau đây.',
            'body' => [
                'Lỗi thứ nhất là trả lời lệch đề. Người viết đọc lướt và bám vào từ khoá chủ đề mà '
                    .'bỏ qua yêu cầu cụ thể của câu hỏi, dẫn tới điểm Task Response thấp dù ngôn ngữ tốt.',
                'Lỗi thứ hai là luận điểm không được phát triển. Mỗi đoạn thân bài cần một luận điểm '
                    .'rõ ràng, kèm giải thích và ví dụ cụ thể thay vì liệt kê nhiều ý nông.',
                'Lỗi thứ ba là lạm dụng từ nối. Sử dụng dày đặc "Moreover", "Furthermore" không tạo '
                    .'mạch lạc; liên kết thực sự đến từ việc lặp lại và phát triển ý một cách logic.',
                'Lỗi thứ tư là dùng từ vựng "học thuộc" sai ngữ cảnh, làm giảm điểm Lexical Resource. '
                    .'Lỗi thứ năm là không dành 3 – 5 phút cuối để soát lỗi ngữ pháp cơ bản.',
            ],
        ],
        [
            'slug' => 'cach-tu-hoc-speaking-tai-nha',
            'title' => 'Tự luyện Speaking tại nhà: quy trình 20 phút mỗi ngày',
            'date' => '15/05/2026',
            'category' => 'Kỹ năng Speaking',
            'excerpt' => 'Một quy trình ngắn nhưng lặp lại đều đặn sẽ hiệu quả hơn nhiều so với việc '
                .'luyện dồn vài giờ trước ngày thi.',
            'body' => [
                'Năm phút đầu dành cho khởi động phát âm: đọc to một đoạn văn ngắn, chú ý trọng âm '
                    .'từ và ngữ điệu câu.',
                'Mười phút tiếp theo luyện trả lời theo bộ đề dự đoán. Hãy ghi âm toàn bộ phần trả '
                    .'lời, đừng dừng lại giữa chừng để sửa.',
                'Năm phút cuối nghe lại bản ghi và ghi chú ba điểm cần cải thiện. Sau một tuần, so '
                    .'sánh bản ghi đầu và cuối để thấy rõ tiến bộ.',
                'Học viên tại trung tâm được gửi bản ghi cho giảng viên nhận xét trong nhóm chữa bài '
                    .'online, nhờ đó lỗi phát âm được phát hiện sớm thay vì lặp lại thành thói quen.',
            ],
        ],
    ],

    // Câu hỏi thường gặp.
    'faqs' => [
        [
            'q' => 'Em mất gốc hoàn toàn thì bắt đầu từ đâu?',
            'a' => 'Học viên sẽ làm bài kiểm tra đầu vào miễn phí để trung tâm xác định trình độ. '
                .'Trường hợp mất gốc, lộ trình phù hợp là khoá IELTS Foundation trước khi lên các '
                .'lớp mục tiêu điểm số.',
        ],
        [
            'q' => 'Trung tâm có cam kết đầu ra không?',
            'a' => 'Có. Cam kết được ghi rõ trong thoả thuận nhập học. Học viên tham gia đủ số buổi '
                .'và hoàn thành bài tập theo yêu cầu nhưng chưa đạt mục tiêu sẽ được học lại miễn '
                .'phí ở khoá kế tiếp.',
        ],
        [
            'q' => 'Một lớp có bao nhiêu học viên?',
            'a' => 'Sĩ số dao động 6 – 14 học viên tuỳ khoá, riêng lớp mục tiêu 7.0+ giới hạn tối đa '
                .'8 học viên để đảm bảo thời lượng chữa bài cho từng bạn.',
        ],
        [
            'q' => 'Em bận lịch học ở trường thì có lớp nào phù hợp?',
            'a' => 'Trung tâm có các khung giờ tối trong tuần, cuối tuần và hình thức 1 kèm 1 cho '
                .'học viên cần lịch linh hoạt.',
        ],
        [
            'q' => 'Nếu nghỉ buổi học thì có được học bù không?',
            'a' => 'Học viên báo trước 24 giờ sẽ được sắp xếp học bù cùng lớp khác đang học chung nội '
                .'dung, hoặc nhận buổi kèm bù với trợ giảng.',
        ],
        [
            'q' => 'Học phí đóng theo hình thức nào?',
            'a' => 'Học phí đóng theo khoá và có thể chia thành hai đợt. Vui lòng liên hệ hotline để '
                .'nhận bảng học phí chi tiết cùng các chương trình ưu đãi đang áp dụng.',
        ],
    ],

    // Danh sách khoá học hiển thị trong form đăng ký tư vấn (được bổ sung tự động).
    'consult_topics' => [
        'Chưa xác định – cần tư vấn lộ trình',
        'Kiểm tra trình độ đầu vào miễn phí',
    ],
];
