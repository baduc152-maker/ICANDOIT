{{-- Bộ biểu tượng dạng inline SVG dùng chung cho toàn website. --}}
@php
    $paths = [
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/>',
        'book' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H19v15H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H19"/>',
        'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M16 5.2a3.2 3.2 0 0 1 0 6"/><path d="M17.5 14.4A6 6 0 0 1 21 20"/>',
        'chart' => '<path d="M4 20V10"/><path d="M10 20V4"/><path d="M16 20v-7"/><path d="M22 20H2"/>',
        'shield' => '<path d="M12 3l7 3v5.5c0 4.3-2.9 8.2-7 9.5-4.1-1.3-7-5.2-7-9.5V6z"/><path d="M9 12l2 2 4-4"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 2"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17"/><path d="M8 3v4M16 3v4"/>',
        'timer' => '<circle cx="12" cy="13" r="8"/><path d="M12 9.5V13l2.5 1.5"/><path d="M9.5 2h5"/>',
        'layers' => '<path d="M12 3l8.5 4.5L12 12 3.5 7.5z"/><path d="M3.5 12.5L12 17l8.5-4.5"/><path d="M3.5 17L12 21.5 20.5 17"/>',
        'arrow-right' => '<path d="M4 12h15"/><path d="M13 6l6 6-6 6"/>',
        'phone' => '<path d="M6.2 3.5h3l1.5 4-2 1.4a12 12 0 0 0 5.4 5.4l1.4-2 4 1.5v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.2 5.7a2 2 0 0 1 2-2.2z"/>',
        'mail' => '<rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="M3.6 7.2L12 13l8.4-5.8"/>',
        'location' => '<path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'star' => '<path d="M12 3.5l2.7 5.7 6.1.8-4.5 4.2 1.2 6-5.5-3-5.5 3 1.2-6-4.5-4.2 6.1-.8z"/>',
        'award' => '<circle cx="12" cy="9" r="5.5"/><path d="M8.5 13.5L7 21l5-2.5L17 21l-1.5-7.5"/>',
        'graduation' => '<path d="M12 4l9 4.2-9 4.2-9-4.2z"/><path d="M6.5 10.4V15c0 1.7 2.5 3 5.5 3s5.5-1.3 5.5-3v-4.6"/>',
        'facebook' => '<path d="M14.5 8.5h2.2V5.6h-2.4c-2.3 0-3.6 1.4-3.6 3.7v1.8H8.4v2.9h2.3V21h3.1v-7h2.4l.4-2.9h-2.8V9.7c0-.8.3-1.2 1.3-1.2z" fill="currentColor" stroke="none"/>',
        'youtube' => '<rect x="2.8" y="5.8" width="18.4" height="12.4" rx="4"/><path d="M10.4 9.7l4.6 2.5-4.6 2.5z" fill="currentColor" stroke="none"/>',
        'chat' => '<path d="M21 12c0 4.1-4 7.4-9 7.4-1 0-2-.1-2.9-.4L4 20.5l1.3-3.4C3.9 15.8 3 14 3 12c0-4.1 4-7.4 9-7.4s9 3.3 9 7.4z"/>',
        'quote' => '<path d="M9.5 6.5C6.5 8 5 10.4 5 13.5c0 2.4 1.4 4 3.4 4s3.3-1.4 3.3-3.3c0-1.8-1.2-3.1-2.9-3.1-.3 0-.6 0-.8.1.4-1.4 1.4-2.6 2.9-3.4zM19.5 6.5c-3 1.5-4.5 3.9-4.5 7 0 2.4 1.4 4 3.4 4s3.3-1.4 3.3-3.3c0-1.8-1.2-3.1-2.9-3.1-.3 0-.6 0-.8.1.4-1.4 1.4-2.6 2.9-3.4z"/>',
    ];
    $name = $name ?? 'check';
    $class = $class ?? '';
@endphp
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
     @if($class) class="{{ $class }}" @endif>
    {!! $paths[$name] ?? $paths['check'] !!}
</svg>
