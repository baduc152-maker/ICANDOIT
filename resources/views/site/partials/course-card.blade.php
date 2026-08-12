{{-- Thẻ tóm tắt một khoá học. --}}
<article class="course-card reveal">
    <div class="course-card__top">
        <span class="course-card__level">{{ $course['level'] }}</span>
        <h3>{{ $course['name'] }}</h3>
        <span>{{ $course['subtitle'] }}</span>
    </div>
    <div class="course-card__body">
        <p>{{ $course['summary'] }}</p>
        <ul class="course-meta">
            <li>@include('site.partials.icon', ['name' => 'timer']) <span>{{ $course['duration'] }}</span></li>
            <li>@include('site.partials.icon', ['name' => 'calendar']) <span>{{ $course['sessions'] }}</span></li>
            <li>@include('site.partials.icon', ['name' => 'users']) <span>Sĩ số {{ $course['class_size'] }}</span></li>
        </ul>
        <div class="course-card__foot">
            <span class="course-card__target">{{ $course['target'] }}</span>
            <a class="btn btn--ghost btn--sm" href="{{ route('site.course', $course['slug']) }}">Chi tiết</a>
        </div>
    </div>
</article>
